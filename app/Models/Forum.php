<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function latestPost()
    {
        return $this->hasOne(ForumPost::class)->latest();
    }
}
