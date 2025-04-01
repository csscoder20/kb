<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_hide',
        'status'
    ];

    /**
     * Relasi Many-to-Many dengan Report
     */
    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_tags');
    }
}
