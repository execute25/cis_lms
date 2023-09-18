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
        'include_groups',
    );

    const TRAINING_TYPE_ZOOM = 0;
    const TRAINING_TYPE_REPEAT_LECTION = 1;


    public function training_live_times()
    {
        return $this->hasMany(TrainingLiveTimeModel::class, 'training_id');
    }

    public function training_repeat_times()
    {
        return $this->hasMany(TrainingRepeatTimeModel::class, 'training_id');
    }


}
