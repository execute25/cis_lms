<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberGroupUserModel extends Model
{
    use HasFactory;

    protected $table = 'membergroup_user';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
