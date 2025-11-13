<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class StudyGroupTodo extends Model
{
    use HasUuid;

    protected $fillable = [
        'study_group_id',
        'title',
        'description',
        'due_date',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completions()
    {
        return $this->hasMany(UserTodoCompletion::class, 'todo_id');
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->completions()
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->exists();
    }
}
