<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberGroupModel extends Model
{
    use HasFactory;

    protected $table = 'membergroups';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


    public function members()
    {
        return $this->belongsToMany(UserModel::class, 'membergroup_user', 'membergroup_id', 'user_id');
    }

}
