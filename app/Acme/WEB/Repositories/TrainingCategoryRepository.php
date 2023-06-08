<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\CellModel;
use App\Models\RegionModel;
use App\Models\TrainingCategoryModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

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

        if (Request::filled("include_groups"))
            $query->include_groups = implode(",", Request::get("include_groups"));


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

        $data = array_filter(Request::all());
        $query->fill($data);

        if (Request::filled("include_groups"))
            $query->include_groups = implode(",", Request::get("include_groups"));


        $query->save();

        return $query;
    }


}
