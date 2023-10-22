<?php

namespace Acme\WEB\Repositories;

use App\Models\TrainingCategoryMemberGroupModel;
use App\Models\TrainingCategoryModel;
use Illuminate\Support\Facades\Request;

class TrainingCategoryRepository
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

    public function createNewTrainingCategory()
    {

        $data = array_filter(Request::all());
        $query = new TrainingCategoryModel(
            $data
        );

        $query->description = Request::filled("description") ? Request::get("description") : '';
        $query->save();

        return $query;
    }

    public function getTrainingCategoryById($id)
    {
        return TrainingCategoryModel::find($id);
    }

    public function updateTrainingCategory($id)
    {
        $query = $this->getTrainingCategoryById($id);
//        $data = array_filter(Request::all());
        $query->fill(Request::all());

        $query->description = Request::filled("description") ? Request::get("description") : '';
        $query->save();


        return $query;
    }

    public function getSelectedMemberGroups($training_category)
    {
        return TrainingCategoryMemberGroupModel::
        where("training_category_id", $training_category->id)
            ->pluck("member_group_id")
            ->toArray();
    }


}
