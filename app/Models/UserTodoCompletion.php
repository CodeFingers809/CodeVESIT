<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTodoCompletion extends Pivot
{
    protected $table = 'user_todo_completions';

    protected $fillable = [
        'user_id',
        'study_group_todo_id',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // This is a pivot table with composite primary key, no single 'id' column
    public $incrementing = false;
    protected $primaryKey = ['user_id', 'study_group_todo_id'];

    public function todo()
    {
        return $this->belongsTo(StudyGroupTodo::class, 'study_group_todo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
