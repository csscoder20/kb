<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    protected $table = 'terms_and_conditions';
    protected $fillable = ['type', 'title', 'content'];

    // Tambahkan ini untuk menghandle JSON
    protected $casts = [
        'content' => 'array'
    ];

    public static function getTerms(string $type): array
    {
        return static::where('type', $type)->first()?->content ?? [];
    }
}
