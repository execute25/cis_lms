<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingRepository;
use App\DataTables\TrainingDataTable;
use App\Helpers\EaseEncrypt;
use App\Models\MemberGroupModel;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingModel;
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

class TrainingController extends BaseController
{

    /**
     * @var TrainingRepository
     */
    private $trainingRepo;
    protected $layout = 'layouts.master';

    public function __construct(TrainingRepository $trainingRepo)
    {
        $this->trainingRepo = $trainingRepo;
    }

    public function index(TrainingDataTable $dataTable)
    {
        $training_category = TrainingCategoryModel::find(Request::get("category_id"));

        return $dataTable->with("category_id", Request::get("category_id", 0))
            ->render('admin.training.index', [
                "training_category" => $training_category
            ]);
    }

    public function create()
    {
        $member_groups = MemberGroupModel::get();

        $this->layout->content = View::make('admin.training.create', [
            "member_groups" => $member_groups,
            "category_id" => Request::get("category_id", 0),
        ]);
    }

    public function store()
    {
        $training = $this->trainingRepo->createNewTraining();

        return Response::json($training);
    }

    public function edit($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);
        $member_groups = MemberGroupModel::get();
        $selected_groups = explode(",", $training->include_groups);
        $this->layout->content = View::make('admin.training.edit', [
            'training' => $training,
            'member_groups' => $member_groups,
            'selected_groups' => $selected_groups,
        ]);
    }

    public function update($id)
    {
        $training = $this->trainingRepo->updateTraining($id);
        return Response::json($training);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        TrainingModel::destroy($id);

        return Response::make('', 200);
    }


}
