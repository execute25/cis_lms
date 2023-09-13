<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomTrainingDataModel extends Model
{
    use HasFactory;

    protected $table = 'zoom_training_data';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
