<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'description',
        'location',
        'image',
        'start_date',
        'end_date',
        'organizer',
        'contact_email',
        'contact_phone',
        'is_featured',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
