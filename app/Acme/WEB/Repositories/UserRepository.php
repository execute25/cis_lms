<?php

namespace Acme\WEB\Repositories;

use App\Helpers\EaseUpload;
use App\Models\UserModel;
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


}
