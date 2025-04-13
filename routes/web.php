<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Filament\Pages\Register;

// Route::get('/', function () {
//     return view('chat');
// });


Route::get('/', [ChatController::class, 'showForm']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/config', [ConfigController::class, 'getConfig']);
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');


// routes/web.php
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/register', [RegisterController::class, 'show'])->name('register');
// Route::get('/tag/{slug}', [TagController::class, 'show'])->name('tag.show');
Route::get('/allposts', [TagController::class, 'show'])->name('tag.show');
Route::get('/newpost', [PostController::class, 'newPost'])->name('tag.newpost');

// Datatables server side processing 
Route::get('/datatable/reports', [TagController::class, 'datatable'])->name('datatable.reports');

// Route::get('/subheader', function (\Illuminate\Http\Request $request) {
//     $slug = $request->query('slug');
//     $tagData = \App\Models\Tag::where('slug', $slug)->first();
//     $basics = \App\Models\Basic::getAllAsArray();

//     return view('components.subheader', compact('tagData', 'basics'))->render();
// });

Route::get('/report/{id}/view-pdf', [TagController::class, 'viewPdf'])->name('report.view.pdf');
Route::get('/report/{id}/download-word', [TagController::class, 'downloadWord'])->name('report.download.word');

Route::get('/search-posts', [TagController::class, 'search'])->name('search.posts');
Route::get('/report/view-pdf/{id}', [TagController::class, 'viewPdf'])->name('report.view.pdf');
Route::get('/report/download-word/{id}', [TagController::class, 'downloadWord'])->name('report.download.word');
