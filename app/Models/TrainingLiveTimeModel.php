<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingLiveTimeModel extends Model
{
    use HasFactory;

    protected $table = 'training_live_times';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
