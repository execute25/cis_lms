<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomAccountConferenceModel extends Model
{
    use HasFactory;

    protected $table = 'zoom_account_conference';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
