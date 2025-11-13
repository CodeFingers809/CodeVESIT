<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTodoCompletion extends Model
{
    protected $table = 'user_todo_completion';

    protected $fillable = [
        'todo_id',
        'user_id',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function todo()
    {
        return $this->belongsTo(StudyGroupTodo::class, 'todo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
