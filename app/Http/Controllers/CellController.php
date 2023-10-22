<?php

namespace App\Http\Controllers;

use Acme\WEB\Repositories\CellRepository;
use App\DataTables\CellDataTable;
use App\Http\Requests\Cell\CellUpdate;
use App\Models\CellModel;
use App\Models\RegionModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use function abort;

class CellController extends BaseController
{

    /**
     * @var CellRepository
     */
    private $cellRepo;
    protected $layout = 'layouts.master';

    public function __construct(CellRepository $cellRepo)
    {
        $this->cellRepo = $cellRepo;
    }

    public function index(CellDataTable $dataTable)
    {
        return $dataTable
            ->render('admin.cell.index', [
            ]);
    }

    public function create()
    {
        $regions = RegionModel::get();

        $this->layout->content = View::make('admin.cell.create', [
            "regions" => $regions
        ]);
    }

    public function store(\App\Http\Requests\Cell\CellStore $request)
    {
        $cell = $this->cellRepo->createNewCell();

        return Response::json($cell);
    }

    public function edit($id)
    {
        $cell = $this->cellRepo->getCellById($id);
        $regions = RegionModel::get();
        $selected_leader = UserModel::find($cell->leader_id);
        $selected_team_leader = UserModel::find($cell->team_leader_id);
        $selected_dep_leader = UserModel::find($cell->dep_leader_id);

        $this->layout->content = View::make('admin.cell.edit', [
            'cell' => $cell,
            'regions' => $regions,
            'selected_leader' => $selected_leader,
            'selected_team_leader' => $selected_team_leader,
            'selected_dep_leader' => $selected_dep_leader,
        ]);
    }

    public function update(CellUpdate $request, $id)
    {
        $cell = $this->cellRepo->updateCell($id);

        return Response::json($cell);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'secretary'])) {
            abort(411);
        }

        CellModel::destroy($id);

        return Response::make('', 200);
    }


    public function attach_member($id)
    {

        $user = UserModel::find(Request::get("member_id"));

        if (!$user->cells->contains($id)) {
            $user->cells()->attach($id);
        }

        return Response::make();
    }

    public function detach_member($id)
    {

        $user = UserModel::find(Request::get("member_id"));

        if ($user->cells->contains($id)) {
            $user->cells()->detach($id);
        }

        return Response::make();
    }

    public function get_member_list($id)
    {
        $members = CellModel::find($id)->members()->get();
        return Response::json($members);
    }


}
