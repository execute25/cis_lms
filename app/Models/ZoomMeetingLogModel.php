<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeetingLogModel extends Model
{
    use HasFactory;

    protected $table = 'zoom_meeting_logs';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
