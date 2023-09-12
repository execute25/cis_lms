<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingRepository;
use Acme\WEB\Repositories\ZoomRepository;
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
    /**
     * @var ZoomRepository
     */
    private $zoomRepository;

    public function __construct(TrainingRepository $trainingRepo, ZoomRepository $zoomRepository)
    {
        $this->trainingRepo = $trainingRepo;
        $this->zoomRepository = $zoomRepository;
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


        $this->layout->content = View::make('admin.training.create', [
            "category_id" => Request::get("category_id", 0),
        ]);
    }

    public function store()
    {
        $training = $this->trainingRepo->createNewTraining();

        if ($training->is_use_zoom == 1) {
            $zoom_meeting = $this->zoomRepository->createMeeting($training);

            if (isset($zoom_meeting['id'])) {
                $training->zoom_conference_id = $zoom_meeting['id'];
                $training->save();
            }
        }


        return Response::json($training);
    }

    public function edit($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);

        $this->layout->content = View::make('admin.training.edit', [
            'training' => $training,
        ]);
    }

    public function update($id)
    {
        $training = $this->trainingRepo->updateTraining($id);

        if ($training->is_use_zoom == 1) {
            $this->zoomRepository->updateMeeging($training);
        }

        return Response::json($training);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        $training = TrainingModel::find($id);

        if ($training) {
            try {
                $this->zoomRepository->deleteMeeting($training);
            } catch (Exception $exception) {
                Log::error("Meeting delete error. Training id:" . $id);
            }
            TrainingModel::destroy($id);
        }


        return Response::make('', 200);
    }


}
