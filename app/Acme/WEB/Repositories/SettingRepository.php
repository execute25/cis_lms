<?php

namespace Acme\WEB\Repositories;

use App\Models\SettingModel;
use Illuminate\Support\Facades\Request;

class SettingRepository
{

    public function __construct()
    {
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
