<?php

namespace Acme\WEB\Repositories;

use App\Models\RegionModel;
use Illuminate\Support\Facades\Request;

class RegionRepository
{

    public function __construct()
    {
    }

    public function createNewRegion()
    {
        $region = new RegionModel(
            Request::all()
        );
        $region->save();

        return $region;
    }

    public function getRegionById($id)
    {
        return RegionModel::find($id);
    }

    public function updateRegion($id)
    {
        $region = $this->getRegionById($id);

        $data = array_filter(Request::all());
        $region->fill($data);
        $region->save();

        return $region;
    }

}
