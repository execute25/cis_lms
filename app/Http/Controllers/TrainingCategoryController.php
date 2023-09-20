<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\SettingRepository;
use Acme\WEB\Repositories\TrainingCategoryRepository;
use App\DataTables\TrainingCategoryDataTable;
use App\Models\MemberGroupModel;
use App\Models\TrainingCategoryModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;

class TrainingCategoryController extends BaseController
{

    /**
     * @var TrainingCategoryRepository
     */
    private $training_categoryRepo;
    protected $layout = 'layouts.master';
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    public function __construct(TrainingCategoryRepository $training_categoryRepo, SettingRepository $settingRepository)
    {
        $this->training_categoryRepo = $training_categoryRepo;
        $this->settingRepository = $settingRepository;
    }

    public function index(TrainingCategoryDataTable $dataTable)
    {
        return $dataTable
            ->render('admin.training_category.index', [
            ]);
    }

    public function create()
    {
        $member_groups = MemberGroupModel::get();
        $zoom_account_settings = $this->settingRepository->getZoomAccountSettings();

        $this->layout->content = View::make('admin.training_category.create', [
            "member_groups" => $member_groups,
            'zoom_account_settings' => $zoom_account_settings,
        ]);
    }

    public function store()
    {
        $training_category = $this->training_categoryRepo->createNewTrainingCategory();

        if (Request::filled("include_groups")) {
            $training_category->membergroups()->sync(Request::get("include_groups"));
        } else {
            $training_category->membergroups()->sync([]);
        }
        $training_category->save();

        return Response::json($training_category);
    }

    public function edit($id)
    {
        $training_category = $this->training_categoryRepo->getTrainingCategoryById($id);
        $member_groups = MemberGroupModel::get();
        $selected_groups = $this->training_categoryRepo->getSelectedMemberGroups($training_category);
        $zoom_account_settings = $this->settingRepository->getZoomAccountSettings();


        $this->layout->content = View::make('admin.training_category.edit', [
            'training_category' => $training_category,
            'member_groups' => $member_groups,
            'selected_groups' => $selected_groups,
            'zoom_account_settings' => $zoom_account_settings,
        ]);
    }

    public function update($id)
    {
        $training_category = $this->training_categoryRepo->updateTrainingCategory($id);


        if (Request::filled("include_groups")) {
            $training_category->membergroups()->sync(Request::get("include_groups"));
        } else {
            $training_category->membergroups()->sync([]);
        }
        $training_category->save();

        return Response::json($training_category);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        TrainingCategoryModel::destroy($id);

        return Response::make('', 200);
    }


}
