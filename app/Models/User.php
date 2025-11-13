<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'year',
        'division',
        'roll_number',
        'bio',
        'avatar_seed',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function getAvatarUrl(): string
    {
        $seed = $this->avatar_seed ?? $this->id;
        return "https://api.dicebear.com/7.x/bottts/svg?seed={$seed}";
    }

    // Study Group Relationships
    public function createdStudyGroups()
    {
        return $this->hasMany(StudyGroup::class, 'created_by');
    }

    public function studyGroupMemberships()
    {
        return $this->hasMany(StudyGroupMember::class);
    }

    public function studyGroups()
    {
        return $this->belongsToMany(StudyGroup::class, 'study_group_members');
    }

    public function moderatingStudyGroups()
    {
        return $this->belongsToMany(StudyGroup::class, 'study_group_moderators');
    }

    // Personal Calendar
    public function calendarEvents()
    {
        return $this->hasMany(PersonalCalendarEvent::class);
    }

    // Forum Relationships
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function forumComments()
    {
        return $this->hasMany(ForumComment::class);
    }

    // Blog Relationships
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function blogRequests()
    {
        return $this->hasMany(BlogRequest::class);
    }

    // Event Relationships
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRequests()
    {
        return $this->hasMany(EventRequest::class);
    }

    // Reports
    public function reports()
    {
        return $this->hasMany(Report::class, 'reported_by');
    }

    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }
}
