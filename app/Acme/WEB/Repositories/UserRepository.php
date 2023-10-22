<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseUpload;
use App\Models\UserModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;

class UserRepository
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

    public function uploadUserImage($user, $files)
    {
        EaseUpload::images($user, $files, $this->options);

        $user->save();
    }

    public function createNewUser()
    {

        $user = new UserModel(
            Request::all()
        );
        $user->save();

        return $user;
    }

    public function createPassword($password)
    {
        return Hash::make($password);
    }

    public function attachRoleToUser($user, $role = false)
    {

        $role = $role ? $role : Request::get("role");

        $role = Role::where('name', $role)->get();
        $user->syncRoles($role);
    }

    public function getUserById($id)
    {
        return UserModel::find($id);
    }

    public function updateUser($id)
    {
        $user = $this->getUserById($id);

        $data = array_filter(Request::all());
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function findNormalUserForLogin()
    {
        $id_number = str_replace("-","",Request::get("email"));
        $id_number = str_replace(" ","",$id_number);
        $id_number = trim($id_number);
        $pattern = '/(\d{8})(\d{5})/';
        $replacement = '$1-$2';
        $id_number = preg_replace($pattern, $replacement, $id_number);

        return UserModel::whereHas('roles', function ($query) {
            $query->whereIn('name', ["cell-leader", "team-leader", "normal"]);
        })->where("id_number", $id_number)->first();
    }

    public function findAdminUserForLogin()
    {
        return UserModel::whereHas('roles', function ($query) {
            $query->whereIn('name', ["super-admin", "secretary"]);
        })
            ->where(function($q){
                $q->where("email", Request::get("email"))
                    ->orWhere("id_number", Request::get("email"));
            })
            ->first();
    }

    public function emptyTimezoneHandler()
    {
        $user = UserModel::find(Auth::user()->id);
        if ($user->timezone == "") {
            $user->timezone = "Asia/Seoul";
            $user->save();
        }
    }

    public function attachToCell(UserModel $user, $data)
    {
        $cellRepo = App::make("Acme\WEB\Repositories\CellRepository");
        $team = isset($data[4]) ? trim(mb_convert_encoding($data[4], 'UTF-8', 'UTF-8')) : ''; // 팀
        $cell_name = trim(mb_convert_encoding($data[7], 'UTF-8', 'UTF-8'));

        if (preg_match('/\d+/', $cell_name, $matches)) {
            $cell_name = $matches[0];
        } else {
            $cell_name = "";
        }

        if ($cell_name == "")
            return;

        $team_leader_cust = isset($data[5]) ? trim(mb_convert_encoding($data[5], 'UTF-8', 'UTF-8')) : ''; // 팀 leader
        if (preg_match('/\d+/', $team_leader_cust, $matches)) {
            $team_leader_cust = $matches[0];
        } else {
            $team_leader_cust = "";
        }

        $team_leader = $this->getUserByCustNumber($team_leader_cust);

        $cell_leader_cust = isset($data[6]) ? trim(mb_convert_encoding($data[6], 'UTF-8', 'UTF-8')) : ''; // 팀 leader
        if (preg_match('/\d+/', $cell_leader_cust, $matches)) {
            $cell_leader_cust = $matches[0];
        } else {
            $cell_leader_cust = "";
        }

        $cell_leader = $this->getUserByCustNumber($cell_leader_cust);

        $cell = $cellRepo->getOrCreateCell($team, $cell_name, $team_leader, $cell_leader);

        $cell->members()->syncWithoutDetaching($user->id);

        return true;
    }

    private function getUserByCustNumber($team_leader_cust)
    {

        return UserModel::where("cust_number", $team_leader_cust)->first();
    }


}
