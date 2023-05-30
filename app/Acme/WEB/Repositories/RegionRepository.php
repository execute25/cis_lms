<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\RegionModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class RegionRepository
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

    public function createNewRegion()
    {
        $region = new RegionModel(
            Request::all()
        );
        $region->save();

        return $region;
    }

    public function getRegionById($id)
    {
        return RegionModel::find($id);
    }

    public function updateRegion($id)
    {
        $region = $this->getRegionById($id);

        $data = array_filter(Request::all());
        $region->fill($data);
        $region->save();

        return $region;
    }

}
