<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRepeatTimeModel extends Model
{
    use HasFactory;

    protected $table = 'training_repeat_times';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
