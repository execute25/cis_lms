<?php

namespace Acme\WEB\Repositories;

use App\Models\MemberGroupModel;
use Illuminate\Support\Facades\Request;

class MemberGroupRepository
{

    public function __construct()
    {
    }

    public function createNewMemberGroup()
    {
        $query = new MemberGroupModel(
            Request::all()
        );
        $query->save();

        return $query;
    }

    public function getMemberGroupById($id)
    {
        return MemberGroupModel::find($id);
    }

    public function updateMemberGroup($id)
    {
        $query = $this->getMemberGroupById($id);

        $data = array_filter(Request::all());
        $query->fill($data);
        $query->save();

        return $query;
    }


}
