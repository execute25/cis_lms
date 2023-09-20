<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomAccountSettingModel extends Model
{
    use HasFactory;

    protected $table = 'zoom_account_settings';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
