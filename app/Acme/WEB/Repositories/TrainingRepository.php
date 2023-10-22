<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseUpload;
use App\Models\MemberGroupUserModel;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingLiveTimeModel;
use App\Models\TrainingModel;
use App\Models\TrainingRepeatTimeModel;
use App\Models\TrainingUserModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Laravel\Nova\Actions\Response;

class TrainingRepository
{

    protected $options = '
		{
			"file_1":[
				{"action":"doc","file_name_field":"file_1_name"}
			],
			"file_2":[
				{"action":"doc","file_name_field":"file_2_name"}
			],
			"file_3":[
				{"action":"doc","file_name_field":"file_3_name"}
			],
			"file_4":[
				{"action":"doc","file_name_field":"file_4_name"}
			],
			"file_5":[
				{"action":"doc","file_name_field":"file_5_name"}
			],
			"file_6":[
				{"action":"doc","file_name_field":"file_6_name"}
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

        $query->description = Request::filled("description") ? Request::get("description") : "";
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

        $live_trainings = TrainingModel::join("training_categories", "training_categories.id", "=", "trainings.category_id")
            ->join("training_live_times", function ($join) use ($currentDateTime) {
                $join->on("training_live_times.training_id", "=", "trainings.id")
                    ->whereRaw("training_live_times.start_at = (
                SELECT MIN(start_at)
                FROM training_live_times AS tl
                WHERE tl.training_id = trainings.id
                AND tl.end_at > '$currentDateTime'
             )");
            })
            ->whereIn("category_id", $allow_category_id)
            ->where(DB::raw("training_live_times.end_at"), '>', $currentDateTime)
            ->where("is_use_zoom", 1)
            ->where("training_categories.is_hidden", 0)
            ->select(
                "trainings.*"
                , "training_categories.title as category_title"
                , "training_categories.is_special"
                , DB::raw("MAX(training_live_times.start_at) as start_at")
                , DB::raw("MAX(training_live_times.end_at) as end_at")
                , DB::raw("CONCAT('0') as training_type")
            )
            ->with([
                "training_live_times" => function ($q) {
                    $q->orderBy("start_at", "asc");
                }, "training_repeat_times" => function ($q) {
                    $q->orderBy("start_at", "asc");
                }
            ])
            ->groupBy(
                "trainings.id",
                "training_categories.title",
                "training_live_times.start_at",
                "training_live_times.end_at"
            )
            ->get();


        $repeat_trainings = TrainingModel::join("training_categories", "training_categories.id", "=", "trainings.category_id")
            ->join("training_repeat_times", function ($join) use ($currentDateTime) {
                $join->on("training_repeat_times.training_id", "=", "trainings.id")
                    ->whereRaw("training_repeat_times.start_at = (
                SELECT MIN(start_at)
                FROM training_repeat_times AS tl
                WHERE tl.training_id = trainings.id
                AND tl.end_at > '$currentDateTime'
             )");
            })
            ->whereIn("category_id", $allow_category_id)
            ->where(DB::raw("training_repeat_times.end_at"), '>', $currentDateTime)
            ->where("is_use_zoom", 1)
            ->where("training_categories.is_hidden", 0)
            ->select(
                "trainings.*"
                , "training_categories.title as category_title"
                , "training_categories.is_special"
                , DB::raw("MAX(training_repeat_times.start_at) as start_at")
                , DB::raw("MAX(training_repeat_times.end_at) as end_at")
                , DB::raw("CONCAT('1') as training_type")
            )
            ->with([
                "training_live_times" => function ($q) {
                    $q->orderBy("start_at", "asc");
                }, "training_repeat_times" => function ($q) {
                    $q->orderBy("start_at", "asc");
                }
            ])
            ->groupBy(
                "trainings.id",
                "training_categories.title",
                "training_repeat_times.start_at",
                "training_repeat_times.end_at"
            )
            ->get();

        $result_object = $repeat_trainings->merge($live_trainings)->unique('id');

        return $result_object->sortBy('start_at');


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

    public function getOrCreateTrainingUserByTrainingId($training_id, $user_id)
    {
        $training_user = TrainingUserModel::where("training_id", $training_id)
            ->where("user_id", $user_id)->first();

        if (!$training_user)
            $training_user = TrainingUserModel::create([
                "training_id" => $training_id,
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

    public function getAvailableTrainginCategories()
    {
        $allow_category_id = $this->getAllowedTrainingCategoryIDs();

        $training_categories = TrainingCategoryModel::whereIn("id", $allow_category_id)
            ->where("is_hidden", 0)
            ->select(
                "training_categories.*"
                , DB::raw(DB::raw("(SELECT COUNT(*) FROM trainings WHERE trainings.category_id = training_categories.id AND trainings.bunny_id != '') as lection_count"))
            )
            ->orderBy("id", "desc")
            ->get();

        return $training_categories;

    }

    public function getTrainingListByCategoryId($category_id)
    {
        $allow_category_id = $this->getAllowedTrainingCategoryIDs();

        $trainings = TrainingModel::
        leftjoin("training_users", "trainings.id", "=", DB::raw("training_users.training_id AND training_users.user_id = " . Auth::user()->id))
            ->whereIn("trainings.category_id", $allow_category_id)->where("trainings.category_id", $category_id)
//            ->where("bunny_id", "!=", "")
            ->select(
                "trainings.*"
                , "training_users.attend_duration"
                , "training_users.watch_time"
                , "training_users.progress"
            )
            ->orderBy("training_users.id", 'desc')
            ->get();


        $result_array = [];
        $currentDateTime = Carbon::now();

        foreach ($trainings as $index => $training) {
            $count_not_ended_live = TrainingLiveTimeModel::where("training_id", $training->id)
                ->where(DB::raw("training_live_times.end_at"), '>', $currentDateTime)
                ->count(); // count of not finished zoom times

            $count_not_ended_repeat = TrainingRepeatTimeModel::where("training_id", $training->id)
                ->where(DB::raw("training_repeat_times.end_at"), '>', $currentDateTime)
                ->count(); // count of not finished repeat times

            if ($count_not_ended_live == 0 && $count_not_ended_repeat == 0) {// exclude lection if have not finished zoom or repeat
                $training->in_process = 0;
            } else {
                $training->in_process = 1;
            }
        }

        return $trainings;

    }

    public function saveImageOfLection($lection, $files)
    {

        EaseUpload::images($lection, $files, $this->options);
        $lection->save();
    }

    public function imageDeleteHandler($model)
    {
        // Delete checked images from disk and clear entry ******
        if (Request::filled("del_yn")) {
            foreach (Request::get("del_yn") as $element_for_delete) {
                if ($element_for_delete == "f1") {
                    $model->file_1 = "";
                    $model->file_1_name = "";
                }

                if ($element_for_delete == "f2") {
                    $model->file_2 = "";
                    $model->file_2_name = "";
                }

                if ($element_for_delete == "f3") {
                    $model->file_3 = "";
                    $model->file_3_name = "";
                }

                if ($element_for_delete == "f4") {
                    $model->file_4 = "";
                    $model->file_4_name = "";
                }

                if ($element_for_delete == "f5") {
                    $model->file_5 = "";
                    $model->file_5_name = "";
                }


                if ($element_for_delete == "f6") {
                    $model->file_6 = "";
                    $model->file_6_name = "";
                }
            }

        }

        $model->save();
        return $model;
    }

    public function getTrainingReports($training_id, $cell_list)
    {

        foreach ($cell_list as $cell) {

            foreach ($cell->members as $member) {
                $training_user = TrainingUserModel::where("training_id", $training_id)->where("user_id", $member->id)->first();
                if ($training_user) {
                    $member->report = $training_user;
                } else {
                    $member->report = [];
                }

            }

        }

        return $cell_list;

    }

    public function isHavePrivileges($member_id, $cell_id)
    {
        if (in_array(Auth::user()->getRoleNames()->toArray()[0], ["super-admin", "secretary"]))
            return true;


        $cellRepo = App::make("Acme\WEB\Repositories\CellRepository");
        $cell = $cellRepo->getCellById($cell_id);

        if (Auth::user()->id == $cell->leader_id || Auth::user()->id == $cell->team_leader_id)
            return true;

        return false;
    }


}
