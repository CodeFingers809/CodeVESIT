<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Forum extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['name', 'description', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($forum) {
            if (empty($forum->slug)) {
                $forum->slug = Str::slug($forum->name) . '-' . Str::random(6);
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function latestPost()
    {
        return $this->hasOne(ForumPost::class)->latest();
    }
}
