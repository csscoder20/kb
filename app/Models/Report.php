<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file',
        'status',
    ];

    /**
     * Relasi Many-to-Many dengan Tag
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'report_tags');
    }
}
