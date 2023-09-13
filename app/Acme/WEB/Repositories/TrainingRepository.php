<?php

namespace Acme\WEB\Repositories;

use App\Models\MemberGroupUserModel;
use App\Models\TrainingModel;
use App\Models\TrainingUserModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->where("trainings.id",$id)
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
        $training_user = TrainingUserModel::where("training_id", $training)
            ->where("user_id", $user_id)->first();

        if (!$training_user)
            $training_user = TrainingUserModel::create([
                "training" => $training,
                "user_id" => $user_id,
            ]);

        return $training_user;

    }

}
