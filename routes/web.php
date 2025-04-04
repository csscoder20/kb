<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('chat');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
