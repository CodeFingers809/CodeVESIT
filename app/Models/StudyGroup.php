<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudyGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'join_code',
        'status',
        'created_by',
        'approved_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($studyGroup) {
            if (empty($studyGroup->join_code)) {
                $studyGroup->join_code = static::generateUniqueJoinCode();
            }
        });
    }

    public static function generateUniqueJoinCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('join_code', $code)->exists());

        return $code;
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'study_group_members')
            ->withTimestamps();
    }

    public function moderators()
    {
        return $this->belongsToMany(User::class, 'study_group_moderators')
            ->withPivot('assigned_by')
            ->withTimestamps();
    }

    public function todos()
    {
        return $this->hasMany(StudyGroupTodo::class);
    }

    public function announcements()
    {
        return $this->hasMany(StudyGroupAnnouncement::class);
    }

    public function messages()
    {
        return $this->hasMany(StudyGroupMessage::class);
    }

    public function calendarEvents()
    {
        return $this->hasMany(StudyGroupCalendarEvent::class);
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isModerator(User $user): bool
    {
        return $this->moderators()->where('user_id', $user->id)->exists();
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }
}
