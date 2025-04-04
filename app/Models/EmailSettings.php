<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
