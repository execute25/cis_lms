<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCategoryMemberGroupModel extends Model
{
    use HasFactory;

    protected $table = 'training_category_membergroup';

    public $timestamps = true;

    protected $guarded = array(
        'id',
    );


}
