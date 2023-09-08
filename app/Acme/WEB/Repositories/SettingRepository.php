<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\SettingModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class SettingRepository
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

    public function createNewSetting()
    {
        $setting = new SettingModel(
            Request::all()
        );
        $setting->save();

        return $setting;
    }

    public function getSettingById($id)
    {
        return SettingModel::find($id);
    }

    public function updateSetting($id)
    {
        $setting = $this->getSettingById($id);

        $data = array_filter(Request::all());
        $setting->fill($data);
        $setting->save();

        return $setting;
    }

    public function getSetting()
    {
        return SettingModel::first();
    }

}
