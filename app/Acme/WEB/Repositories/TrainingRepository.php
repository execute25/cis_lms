<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\CellModel;
use App\Models\RegionModel;
use App\Models\TrainingModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

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

    public function updateTraining($id)
    {
        $query = $this->getTrainingById($id);

        $data = array_filter(Request::all());
        $query->fill($data);

        if (Request::filled("include_groups")){
            $query->include_groups = implode(",", Request::get("include_groups"));
        }else{
            $query->include_groups = "";
        }



        $query->save();

        return $query;
    }


}
