<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\SettingRepository;
use Acme\WEB\Repositories\TrainingCategoryRepository;
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
    /**
     * @var TrainingCategoryRepository
     */
    private $trainingCategoryRepository;
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @var TrainingCategoryRepository
     */

    public function __construct(TrainingRepository $trainingRepo, ZoomRepository $zoomRepository, TrainingCategoryRepository $trainingCategoryRepository, SettingRepository $settingRepository)
    {
        $this->trainingRepo = $trainingRepo;
        $this->zoomRepository = $zoomRepository;
        $this->trainingCategoryRepository = $trainingCategoryRepository;
        $this->settingRepository = $settingRepository;
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

        if (Request::filled("training_live_time"))
            $this->trainingRepo->trainingLiveTimeHandler($training);


        if (Request::filled("training_repeat_time"))
            $this->trainingRepo->trainingRepeatTimeHandler($training);

        if ($training->is_use_zoom == 1) {
            $training_category = $this->trainingCategoryRepository->getTrainingCategoryById($training->category_id);
            $zoom_setting = $this->settingRepository->getSettingByEmail($training_category->zoom_host_email);
            $zoom_meeting = $this->zoomRepository->createMeeting($training, $zoom_setting);


            if (isset($zoom_meeting['id'])) {
                $zoom_conference_id = $zoom_meeting['id'];
                $training->zoom_conference_id = $zoom_conference_id;
                $training->save();

                $this->settingRepository->createUpdateAccountConference($zoom_conference_id, $zoom_setting);

            }

            return Response::json($zoom_meeting);
        }




        return Response::json("");
    }

    public function edit($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);

        $training_live_times = $this->trainingRepo->getTrainingLiveTimeByTrainingId($id);
        $training_repeat_times = $this->trainingRepo->getTrainingRepeatTimeByTrainingId($id);


        $this->layout->content = View::make('admin.training.edit', [
            'training' => $training,
            'training_live_times' => $training_live_times->toArray(),
            'training_repeat_times' => $training_repeat_times->toArray(),
        ]);
    }

    public function update($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);

        $training = $this->trainingRepo->updateTraining($training);

        if (Request::filled("training_live_time"))
            $this->trainingRepo->trainingLiveTimeHandler($training);


        if (Request::filled("training_repeat_time"))
            $this->trainingRepo->trainingRepeatTimeHandler($training);


        if ($training->is_use_zoom == 1 && $training->name != Request::get("name")) {
            $training_category = $this->trainingCategoryRepository->getTrainingCategoryById($training->category_id);
            $zoom_setting = $this->settingRepository->getSettingByEmail($training_category->zoom_host_email);
            $this->zoomRepository->updateMeeting($training, $zoom_setting);
        }

        $this->trainingRepo->saveImageOfLection($training, Request::file());
        $this->trainingRepo->imageDeleteHandler($training);
        $training->save();

        return Response::json($training);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        $training = TrainingModel::find($id);

        if ($training) {

            if ($training->zoom_conference_id != "") {
                try {

                    $training_category = $this->trainingCategoryRepository->getTrainingCategoryById($training->category_id);
                    $zoom_setting = $this->settingRepository->getSettingByEmail($training_category->zoom_host_email);

                    $this->zoomRepository->deleteMeeting($training, $zoom_setting);
                } catch (Exception $exception) {
                    Log::error("Meeting delete error. Training id:" . $id);
                }
            }


            TrainingModel::destroy($id);

        }


        return Response::make('', 200);
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

            $training_category = $this->trainingCategoryRepository->getTrainingCategoryById($training->category_id);
            $zoom_setting = $this->settingRepository->getSettingByEmail($training_category->zoom_host_email);

            $user_training->join_zoom_link = $this->zoomRepository->makeTrainingJoinZoomLink($training,$zoom_setting);
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

        if (!$training_user) {
            $training_user = $this->trainingRepo->getOrCreateTrainingUserByTrainingId($training, Auth::user()->id);
        }


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


    public function upcoming_trainings()
    {
        $trainings = $this->trainingRepo->getUpcomingTrainings();

        $this->layout->content = View::make('web.training.upcoming_trainings', [
            "trainings" => $trainings
        ]);
    }


    public function available_training_categories()
    {
        $training_categories = $this->trainingRepo->getAvailableTrainginCategories();

        $this->layout->content = View::make('web.training.available_training_categories', [
            "training_categories" => $training_categories
        ]);
    }

    public function training_list()
    {
        $category_id = Request::get("category_id");
        $trainings = $this->trainingRepo->getTrainingListByCategoryId($category_id);
        $category = $this->trainingCategoryRepository->getTrainingCategoryById($category_id);

        $this->layout->content = View::make('web.training.training_list', [
            "trainings" => $trainings,
            "category" => $category,
        ]);
    }

    public function attendance_list()
    {
        $category_id = Request::get("category_id");
        $trainings = $this->trainingRepo->getTrainingListByCategoryId($category_id);
        $category = $this->trainingCategoryRepository->getTrainingCategoryById($category_id);

        $this->layout->content = View::make('web.training.training_list', [
            "trainings" => $trainings,
            "category" => $category,
        ]);
    }

    public function material_list($id)
    {
        $training = $this->trainingRepo->getTrainingById($id);

        $this->layout->content = View::make('web.training.material_list', [
            "training" => $training,
        ]);
    }


}
