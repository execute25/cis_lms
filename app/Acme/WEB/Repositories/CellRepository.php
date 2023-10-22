<?php

namespace Acme\WEB\Repositories;

use App\Models\CellModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CellRepository
{

    public function __construct()
    {
    }

    public function createNewCell()
    {
        $user = new CellModel(
            Request::all()
        );
        $user->save();

        return $user;
    }

    public function getCellById($id)
    {
        return CellModel::find($id);
    }

    public function updateCell($id)
    {
        $cell = $this->getCellById($id);

        $data = array_filter(Request::all());
        $cell->fill($data);
        $cell->save();

        return $cell;
    }

    public function getCellListOfUser()
    {
        return CellModel::
        select("id", "name", "team")
            ->where("team_leader_id", Auth::user()->id)
            ->orWhere("leader_id", Auth::user()->id)
            ->orWhere("dep_leader_id", Auth::user()->id)
            ->with("members")
            ->get();
    }

    public function getOrCreateCell($team, $cell_name, $team_leader, $cell_leader)
    {
        $cell = CellModel::where("team", $team)
            ->where("name", $cell_name)->first();

        if (!$cell)
            $cell = CellModel::create([
                "team" => $team,
                "name" => $cell_name,
            ]);

        $cell->team_leader_id = $team_leader->id;
        $cell->leader_id = $cell_leader->id;
        $cell->save();


        return $cell;
    }

}
