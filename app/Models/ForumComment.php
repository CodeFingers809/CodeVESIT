<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasUuid;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'parent_id',
    ];

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(ForumComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumComment::class, 'parent_id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
