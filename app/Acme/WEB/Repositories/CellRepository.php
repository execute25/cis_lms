<?php

namespace Acme\WEB\Repositories;

use App\Models\CellModel;
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

}
