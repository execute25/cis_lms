<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'training_categories';

    public $timestamps = true;

    protected $guarded = array(
        'id',
        'include_groups',
    );


    public function membergroups()
    {
        return $this->belongsToMany(MemberGroupModel::class, 'training_category_membergroup', 'training_category_id', 'member_group_id');
    }

}
