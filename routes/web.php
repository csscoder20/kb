<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ConfigController;

Route::get('/', function () {
    return view('chat');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/config', [ConfigController::class, 'getConfig']);
