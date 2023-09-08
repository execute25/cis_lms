<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\SettingRepository;
use App\DataTables\SettingDataTable;
use App\Helpers\EaseEncrypt;
use App\Models\SettingModel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;
use function iconv;
use function redirect;

class SettingController extends BaseController
{

    /**
     * @var SettingRepository
     */
    private $settingRepo;
    protected $layout = 'layouts.master';

    public function __construct(SettingRepository $settingRepo)
    {
        $this->settingRepo = $settingRepo;
    }


    public function change_setting()
    {
        $setting = $this->settingRepo->getSetting();
        if (!$setting)
            $setting =  SettingModel::create([]);

        $this->layout->content = View::make('admin.setting.edit')
            ->with('setting', $setting);
    }

    public function update($id)
    {
        $setting = $this->settingRepo->updateSetting($id);
        return Response::json($setting);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        SettingModel::destroy($id);

        return Response::make('', 200);
    }


}
