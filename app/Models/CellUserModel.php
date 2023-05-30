<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellUserModel extends Model
{
    protected $table = 'cell_user';

    public $timestamps = true;
    protected $guarded = array(
        'id',
    );

}
