<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\State;

class Country extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'code',
        'phonecode',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
