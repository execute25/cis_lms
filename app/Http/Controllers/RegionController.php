<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\RegionRepository;
use App\DataTables\RegionDataTable;
use App\Models\RegionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;

class RegionController extends BaseController
{

    /**
     * @var RegionRepository
     */
    private $regionRepo;
    protected $layout = 'layouts.master';

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepo = $regionRepo;
    }

    public function index(RegionDataTable $dataTable)
    {
        return $dataTable
            ->render('admin.region.index', [
            ]);
    }

    public function create()
    {
        $this->layout->content = View::make('admin.region.create', [
        ]);
    }

    public function store()
    {
        $region = $this->regionRepo->createNewRegion();

        return Response::json($region);
    }

    public function edit($id)
    {
        $region = $this->regionRepo->getRegionById($id);

        $this->layout->content = View::make('admin.region.edit')
            ->with('region', $region);
    }

    public function update($id)
    {
        $region = $this->regionRepo->updateRegion($id);
        return Response::json($region);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        RegionModel::destroy($id);

        return Response::make('', 200);
    }


}
