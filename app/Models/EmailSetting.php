<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
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
