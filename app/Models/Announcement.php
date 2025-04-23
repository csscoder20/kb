<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'type',
        'is_active',
        'start_date',
        'end_date',
        'show_once_per_session'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'show_once_per_session' => 'boolean'
    ];
}
