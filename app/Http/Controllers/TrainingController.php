<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingRepository;
use Acme\WEB\Repositories\ZoomRepository;
use App\DataTables\TrainingDataTable;
use App\Models\TrainingCategoryModel;
use App\Models\TrainingModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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

        $training_live_times = $this->trainingRepo->getTrainingLiveTimeByTrainingId($id);
        $training_repeat_times = $this->trainingRepo->getTrainingRepeatTimeByTrainingId($id);

//        return $training_live_time->toArray();

        $this->layout->content = View::make('admin.training.edit', [
            'training' => $training,
            'training_live_times' => $training_live_times->toArray(),
            'training_repeat_times' => $training_repeat_times->toArray(),
        ]);
    }

    public function update($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);


        if ($training->is_use_zoom == 1 && $training->name != Request::get("name")) {
            $this->zoomRepository->updateMeeging($training);
        }

        $training = $this->trainingRepo->updateTraining($training);

        if(Request::filled("training_live_time"))
            $this->trainingRepo->trainingLiveTimeHandler($training);


        if(Request::filled("training_repeat_time"))
            $this->trainingRepo->trainingRepeatTimeHandler($training);

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


    public function show_video($training_id)
    {
        $training = $this->trainingRepo->getTrainingByIdWith($training_id);
        $training_user = $this->trainingRepo->getTrainingUserById($training_id);

        $this->layout->content = View::make('web.training.show_video', [
            "training" => $training,
            "training_user" => $training_user,
        ]);

    }


    public function update_watch_point($id)
    {
        $training_user = $this->trainingRepo->getTrainingUserById($id);
        $training = $this->trainingRepo->getTrainingById($id);

        if (!$training_user)
            return Response::make('', 410);

//        if ($training_user->status == 0) {
//            $training_user->status = 1;
//            $training_user->save();
//        }


        $duration_diff = round($training->duration) - round(Request::get("duration"));
//        echo "diff " . $duration_diff;


        if (($duration_diff > 2 || $duration_diff < -2) || $training_user->video_duration == "") {
//            echo "not";
//            echo "lection->duration " . round($training->duration);
//            echo "round(Input::get(duration) " . round(Request::get("duration"));
            $this->trainingRepo->updateLectionDuration($training, Request::get("duration"));
        }


        if ($training_user->watch_time < Request::get("watching_seconds")) {
            $training_user = $this->trainingRepo->updateWatchPoint($training_user);
        }


        return Response::make($training_user);
    }


}
