<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\CellModel;
use App\Models\MemberGroupModel;
use App\Models\RegionModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class MemberGroupRepository
{

    protected $options = '
		{
			"photo_1":[
				{"action":"grab","width":512, "height":512},
				{"action":"thumbnail", "ratio":0.5, "target":"photo_1_thumb" }
			]
		}
	';

    public function __construct()
    {
        $this->options = json_decode($this->options);
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
