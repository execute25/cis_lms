<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingCategoryRepository;
use Acme\WEB\Repositories\ZoomRepository;
use App\DataTables\TrainingCategoryDataTable;
use App\Helpers\EaseEncrypt;
use App\Models\MemberGroupModel;
use App\Models\TrainingCategoryModel;
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

class TrainingCategoryController extends BaseController
{

    /**
     * @var TrainingCategoryRepository
     */
    private $training_categoryRepo;
    protected $layout = 'layouts.master';

    public function __construct(TrainingCategoryRepository $training_categoryRepo)
    {
        $this->training_categoryRepo = $training_categoryRepo;
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

        $this->layout->content = View::make('admin.training_category.create', [
            "member_groups" => $member_groups
        ]);
    }

    public function store()
    {
        $training_category = $this->training_categoryRepo->createNewTrainingCategory();


        return Response::json($training_category);
    }

    public function edit($id)
    {
        $training_category = $this->training_categoryRepo->getTrainingCategoryById($id);
        $member_groups = MemberGroupModel::get();
        $selected_groups = explode(",", $training_category->include_groups);
        $this->layout->content = View::make('admin.training_category.edit', [
            'training_category' => $training_category,
            'member_groups' => $member_groups,
            'selected_groups' => $selected_groups,
        ]);
    }

    public function update($id)
    {
        $training_category = $this->training_categoryRepo->updateTrainingCategory($id);
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
