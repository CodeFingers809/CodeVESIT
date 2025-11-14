<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class StudyGroupAnnouncement extends Model
{
    use HasUuid;

    protected $fillable = [
        'study_group_id',
        'title',
        'content',
        'created_by',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
