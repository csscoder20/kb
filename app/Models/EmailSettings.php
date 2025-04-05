<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\EmailSettingsObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([EmailSettingsObserver::class])]

class EmailSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'driver',
        'secret_key',
        'domain',
        'region',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'status'
    ];
}
