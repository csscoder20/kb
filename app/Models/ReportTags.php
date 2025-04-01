<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportTags extends Model
{
    use HasFactory;

    protected $table = 'report_tags'; // Pastikan sesuai dengan nama tabel pivot

    protected $fillable = [
        'report_id',
        'tag_id',
    ];
}
