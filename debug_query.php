<?php

use App\Models\Announcement;

$announcement = Announcement::query()
    ->where('is_active', true)
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->latest()
    ->first();

dd($announcement);
