<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellModel extends Model
{
    use HasFactory;

    protected $table = 'cells';

    public $timestamps = true;
    protected $guarded = array(
        'id',
    );

    public function members()
    {
        return $this->belongsToMany(UserModel::class, 'cell_user', 'cell_id', 'user_id');
    }

}
