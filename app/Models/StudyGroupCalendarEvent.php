<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyGroupCalendarEvent extends Model
{
    use HasUuid;

    use HasFactory;

    protected $fillable = [
        'study_group_id',
        'created_by',
        'title',
        'description',
        'event_date',
        'priority',
        'is_completed',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
