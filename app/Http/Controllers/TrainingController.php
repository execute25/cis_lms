<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\TrainingRepository;
use App\DataTables\TrainingDataTable;
use App\Helpers\EaseEncrypt;
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
        return $dataTable
            ->render('admin.training.index', [
            ]);
    }

    public function create()
    {
        $this->layout->content = View::make('admin.training.create', [
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

        $this->layout->content = View::make('admin.training.edit')
            ->with('training', $training);
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
