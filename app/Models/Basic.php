<?php

namespace App\Models;

use App\Observers\BasicObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([BasicObserver::class])]
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
