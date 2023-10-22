<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Maatwebsite\Excel\Cell;
use Spatie\Permission\Traits\HasRoles;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = array(
        'id',
        'role',
    );

    protected $table = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function getRolesList()
    {
        return [
            "super-admin" => "Main Admin",
            "secretary" => "Secretary",
            "team-leader" => "Team Leader",
            "cell-leader" => "Cell Leader",
            "normal" => "Normal User",
        ];
    }

    public static function getDepartmentList()
    {
        return [
            "0" => "Male department",
            "1" => "Women's department",
            "2" => "Youth department",
            "3" => "Old-Aged department",
        ];
    }

    public static function isLeader()
    {

        if (in_array(Auth::user()->getRoleNames()->toArray()[0], ["super-admin", "secretary"]))
            return true;
        
        $cell = CellModel::where("leader_id", Auth::user()->id)->orWhere("team_leader_id", Auth::user()->id)->first();

        if ($cell)
            return true;

        return false;
    }

    public function cells()
    {
        return $this->belongsToMany(CellModel::class, 'cell_user', 'user_id', 'cell_id');
    }

    public function membergroups()
    {
        return $this->belongsToMany(MemberGroupModel::class, 'membergroup_user', 'user_id', 'membergroup_id');
    }
}
