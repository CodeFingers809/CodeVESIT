<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'slug',
        'organizer_id',
        'description',
        'start_date',
        'end_date',
        'location',
        'featured_image',
        'max_participants',
        'is_published',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
