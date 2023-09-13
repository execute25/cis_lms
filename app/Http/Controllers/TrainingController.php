<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingRepository;
use Acme\WEB\Repositories\ZoomRepository;
use App\DataTables\TrainingDataTable;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;

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
        $training = $this->trainingRepo->getTrainingById($id);


        if ($training->is_use_zoom == 1 && $training->name != Request::get("name")) {
            $this->zoomRepository->updateMeeging($training);
        }

        $training = $this->trainingRepo->updateTraining($training);


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


    public function upcoming_trainings()
    {
        $trainings = $this->trainingRepo->getUpcomingTrainings();


        $this->layout->content = View::make('web.training.upcoming_trainings', [
            "trainings" => $trainings
        ]);

    }

    public function get_zoom_join_link($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);

        if ($training->is_use_zoom == 0)
            return Response::make("", 411);


        $user_training = $this->trainingRepo->getUserTraining($id);

        if ($user_training->join_zoom_link == "") {

            if ($training->zoom_conference_id == "")
                return Response::make("Zoom conference ID empty", 412);

            $user_training->join_zoom_link = $this->zoomRepository->makeTrainingJoinZoomLink($training);
            $user_training->save();
        }

        return Response::json($user_training);


    }


    public function show_video($id)
    {
        $training = $this->trainingRepo->getTrainingByIdWith($id);

        $this->layout->content = View::make('web.training.show_video', [
            "training" => $training
        ]);

    }


}
