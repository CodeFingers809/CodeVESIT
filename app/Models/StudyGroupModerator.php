<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudyGroupModerator extends Pivot
{
    protected $fillable = [
        'study_group_id',
        'user_id',
    ];

    // This is a pivot table with composite primary key, no single 'id' column
    public $incrementing = false;
    protected $primaryKey = ['study_group_id', 'user_id'];
}
