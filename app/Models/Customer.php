<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([CustomerObserver::class])]

class Customer extends Model
{
    protected $fillable = ['name'];
}
