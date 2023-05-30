<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingModel extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
