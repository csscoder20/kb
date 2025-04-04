<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Basic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'banner',
        'banner_description',
        'homepage',
        'color',
        'is_darkmode_active',
        'logo',
        'favicon'
    ];
}
