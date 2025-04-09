<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\RegisterController;
use App\Filament\Pages\Register;

// Route::get('/', function () {
//     return view('chat');
// });


Route::get('/', [ChatController::class, 'showForm']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/config', [ConfigController::class, 'getConfig']);
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');


// routes/web.php
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::get('/admin/register', Register::class)->name('filament.pages.register');
