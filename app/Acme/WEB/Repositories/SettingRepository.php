<?php

namespace Acme\WEB\Repositories;

use App\Models\SettingModel;
use App\Models\TrainingLiveTimeModel;
use App\Models\TrainingRepeatTimeModel;
use App\Models\ZoomAccountConferenceModel;
use App\Models\ZoomAccountSettingModel;
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

    public function zoomSettingsTimeHandler($setting)
    {

        $zoom_settings = Request::get("zoom_settings");


        ZoomAccountSettingModel::query()->truncate();

        foreach ($zoom_settings as $index => $item) {
            if (!$item['host_email'] || $item['host_email'] == "" || (isset($item['is_delete']) && $item['is_delete'] == 1))
                continue;

            ZoomAccountSettingModel::create([
                "host_email" => $item['host_email'],
                "zoom_account_id" => $item['zoom_account_id'],
                "zoom_client_id" => $item['zoom_client_id'],
                "zoom_client_secret" => $item['zoom_client_secret'],
                "zoom_redirect_url" => $item['zoom_redirect_url'],
            ]);
        }

    }

    public function getZoomSettings()
    {
        return ZoomAccountSettingModel::orderBY("id")->get();
    }


    public function getZoomAccountSettings()
    {
        return ZoomAccountSettingModel::all();
    }

    public function getSettingByEmail($host_email)
    {
        return ZoomAccountSettingModel::where("host_email", $host_email)->first();
    }

    public function createUpdateAccountConference($zoom_conference_id, $zoom_setting)
    {
        $account_conference = ZoomAccountConferenceModel::where("conference_id", $zoom_conference_id)->first();

        if (!$account_conference)
            $account_conference = ZoomAccountConferenceModel::create(["conference_id" => $zoom_conference_id]);

        $account_conference->host_email = $zoom_setting->host_email;
        $account_conference->save();
    }

}
