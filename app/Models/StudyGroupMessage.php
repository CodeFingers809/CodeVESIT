<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroupMessage extends Model
{
    protected $fillable = [
        'study_group_id',
        'user_id',
        'message',
        'is_reported',
    ];

    protected $casts = [
        'is_reported' => 'boolean',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
