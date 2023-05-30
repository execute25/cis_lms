<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseEncrypt;
use App\Helpers\EaseUpload;
use App\Models\CellModel;
use App\Models\UserModel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class CellRepository
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
