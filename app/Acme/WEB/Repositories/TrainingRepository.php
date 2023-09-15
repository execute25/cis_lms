<?php

namespace Acme\WEB\Repositories;

use App\Models\MemberGroupUserModel;
use App\Models\TrainingLiveTimeModel;
use App\Models\TrainingModel;
use App\Models\TrainingRepeatTimeModel;
use App\Models\TrainingUserModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class TrainingRepository
{

    protected $options = '
		{
			"photo_1":[
				{"action":"grab","width":512, "height":512},
				{"action":"thumbnail", "ratio":0.5, "target":"photo_1_thumb" }
			]
		}
	';

    public function __construct()
    {
        $this->options = json_decode($this->options);
    }

    public function createNewTraining()
    {

        $data = array_filter(Request::all());
        $query = new TrainingModel(
            $data
        );

        if (Request::filled("include_groups"))
            $query->include_groups = implode(",", Request::get("include_groups"));


        $query->save();

        return $query;
    }

    public function getTrainingById($id)
    {
        return TrainingModel::find($id);
    }

    public function getTrainingByIdWith($id)
    {
        $trainings = TrainingModel::query();
        $trainings = $trainings
            ->join("training_categories", "training_categories.id", "=", "trainings.category_id")
            ->where("trainings.id", $id)
            ->select(
                "trainings.*"
                , "training_categories.title as category_title"
            )
            ->first();

        return $trainings;
    }

    public function updateTraining($query)
    {
        $data = array_filter(Request::all());
        $query->fill($data);

        if (Request::filled("include_groups")) {
            $query->include_groups = implode(",", Request::get("include_groups"));
        } else {
            $query->include_groups = "";
        }


        $query->save();

        return $query;
    }

    public function getUpcomingTrainings()
    {
        $allow_category_id = $this->getAllowedTrainingCategoryIDs();

        $currentDateTime = Carbon::now();

        $trainings = TrainingModel::query();
        $trainings = $trainings
            ->join("training_categories", "training_categories.id", "=", "trainings.category_id")
            ->whereIn("category_id", $allow_category_id)
            ->where(DB::raw("CONCAT(end_at, ' ', end_at_time,':00')"), '>', $currentDateTime)
            ->where("is_use_zoom", 1)
            ->where("training_categories.is_hidden", 0)
            ->select(
                "trainings.*"
                , "training_categories.title as category_title"
            )
            ->orderBY("start_at", "asc")
            ->orderBY("start_at_time", "asc")
            ->get();

        return $trainings;


    }

    private function getAllowedTrainingCategoryIDs()
    {
        $get_allowed_categories_for_all = DB::table('training_categories as tc')
            ->leftJoin('training_category_membergroup as tcmg', 'tc.id', '=', 'tcmg.training_category_id')
            ->whereNull('tcmg.training_category_id')
            ->select('tc.*')
            ->pluck("tc.id")->toArray();


        $get_allowed_categories = MemberGroupUserModel::
        join("membergroups", "membergroups.id", "=", "membergroup_user.membergroup_id")
            ->join("training_category_membergroup", "training_category_membergroup.member_group_id", "=", "membergroups.id")
            ->where("membergroup_user.user_id", Auth::user()->id)
            ->pluck("training_category_membergroup.training_category_id")->toArray();

        $result = array_merge($get_allowed_categories_for_all, $get_allowed_categories);

        return $result;
    }

    public function getUserTraining($id)
    {
        $user_training = TrainingUserModel::where("user_id", Auth::user()->id)
            ->where("training_id", $id)
            ->first();

        if (!$user_training)
            $user_training = TrainingUserModel::create([
                "user_id" => Auth::user()->id,
                "training_id" => $id,
                "join_time" => "",
                "leave_time" => "",
                "duration" => 0,
            ]);


        return $user_training;

    }


    public function getTrainingByMeetingID($meeting_id)
    {
        return TrainingModel::where("zoom_conference_id", $meeting_id)->first();
    }

    public function getOrCreateTrainingUserByTrainingId($training, $user_id)
    {
        $training_user = TrainingUserModel::where("training_id", $training->id)
            ->where("user_id", $user_id)->first();

        if (!$training_user)
            $training_user = TrainingUserModel::create([
                "training_id" => $training->id,
                "user_id" => $user_id,
            ]);

        return $training_user;

    }

    public function getTrainingUserWith($training_id)
    {
        return TrainingUserModel::
        join("trainings", "trainings.id", "=", "training_user.id")
            ->where("user_id", Auth::user()->id)
            ->where("training_id", $training_id)
            ->select("training_users.*", "trainings.name as training_name")
            ->first();

    }

    public function getTrainingUserById($id)
    {
        return TrainingUserModel::where("user_id", Auth::user()->id)
            ->where("training_id", $id)
            ->first();
    }


    public function updateLectionDuration($training, $duration)
    {


        $training->duration = round($duration);
        $training->save();


        \App\Models\TrainingUserModel::where("training_id", $training->id)
            ->where("video_duration", "!=", round($duration))
            ->update([
                "video_duration" => round($duration),
            ]);
    }

    public function updateWatchPoint($training_user)
    {

        $video_duration = $training_user->video_duration == "" ? 0 : $training_user->video_duration;
        $training_user->watch_time = Request::get("watching_seconds");
        $progress = $video_duration != 0 ? Request::get("watching_seconds") * 100 / $video_duration : 0;
        $training_user->progress = $progress == 99 ? 100 : $progress;


        if ($training_user->watch_start_at == "")
            $training_user->watch_start_at = date("Y-m-d H:i:s");

        $training_user->save();

        return $training_user;
    }

    public function trainingLiveTimeHandler($training)
    {
        $training_live_time = Request::get("training_live_time");


        TrainingLiveTimeModel::where("training_id", $training->id)->delete();

        foreach ($training_live_time as $index => $item) {
            if (!$item['start_at'] || $item['start_at'] == "" || $item['is_delete'] == 1)
                continue;

            TrainingLiveTimeModel::create([
                "training_id" => $training->id,
                "start_at" => $item['start_at'],
                "end_at" => $item['end_at'],
            ]);
        }
    }

    public function getTrainingLiveTimeByTrainingId($id)
    {
        return TrainingLiveTimeModel::where("training_id", $id)->get();
    }

    public function trainingRepeatTimeHandler($training)
    {
        $training_repeat_time = Request::get("training_repeat_time");


        TrainingRepeatTimeModel::where("training_id", $training->id)->delete();

        foreach ($training_repeat_time as $index => $item) {
            if (!$item['start_at'] || $item['start_at'] == "" || $item['is_delete'] == 1)
                continue;

            TrainingRepeatTimeModel::create([
                "training_id" => $training->id,
                "start_at" => $item['start_at'],
                "end_at" => $item['end_at'],
            ]);
        }
    }

    public function getTrainingRepeatTimeByTrainingId($id)
    {
        return TrainingRepeatTimeModel::where("training_id", $id)->get();
    }


}
