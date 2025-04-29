<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\TermsController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FileAccessController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\RegisterController;

// Public routes
Route::get('/', [ChatController::class, 'showForm']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/config', [ConfigController::class, 'getConfig']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/quick-search', [ChatController::class, 'ask'])->name('tag.ask');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/write-newpost', [PostController::class, 'newPost'])->name('tag.newpost');
    // File access routes
    Route::get('/reports/view-pdf/{id}', [ReportController::class, 'viewPdf'])->name('report.view.pdf');
    Route::get('/reports/download-word/{id}', [ReportController::class, 'downloadWord'])->name('report.download.word');
    // Route::post('/log-file-access', [FileAccessController::class, 'store'])->name('file-access.store');
    Route::post('/log-file-access', [ActivityLogController::class, 'store'])->name('file-access.store');
});

// Redirect unauthorized access to Filament login
Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');


// routes/web.php
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::get('/allposts', [TagController::class, 'show'])->name('tag.show');

// Datatables server side processing 
Route::get('/datatable/reports', [TagController::class, 'datatable'])->name('datatable.reports');

Route::get('/report/{id}/view-pdf', [TagController::class, 'viewPdf'])->name('report.view.pdf');
Route::get('/report/{id}/download-word', [TagController::class, 'downloadWord'])->name('report.download.word');

Route::get('/search-posts', [TagController::class, 'search'])->name('search.posts');
Route::get('/report/view-pdf/{id}', [TagController::class, 'viewPdf'])->name('report.view.pdf');
Route::get('/report/download-word/{id}', [TagController::class, 'downloadWord'])->name('report.download.word');

// Route Customer
Route::get('/customers/select', [CustomerController::class, 'select']);



// Google Login
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    if (!$googleUser->getEmail()) {
        abort(403, 'Email not found in Google account.');
    }

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random(16)),
        ]
    );

    if (!$user->hasAnyRole()) {
        $user->assignRole('user');
    }

    Auth::login($user);

    return redirect('/');
});

// Microsoft Login
Route::get('/auth/microsoft', function () {
    return Socialite::driver('microsoft')->redirect();
})->name('login.microsoft');

Route::get('/auth/microsoft/callback', function () {
    $microsoftUser = Socialite::driver('microsoft')->stateless()->user();

    if (!$microsoftUser->getEmail()) {
        abort(403, 'Email not found in Microsoft account.');
    }

    // Optional: Validasi domain tertentu
    // if (!str_ends_with($microsoftUser->getEmail(), '@yourdomain.com')) {
    //     abort(403, 'Unauthorized domain.');
    // }

    $user = User::updateOrCreate(
        ['email' => $microsoftUser->getEmail()],
        [
            'name' => $microsoftUser->getName(),
            'microsoft_id' => $microsoftUser->getId(), // Opsional: simpan ID
            'password' => bcrypt(Str::random(16)),
        ]
    );

    Auth::login($user);

    return redirect('/admin');
});

Route::get('/api/announcement/active', [AnnouncementController::class, 'getActive']);

Route::post('/verify-recaptcha', function (Request $request) {
    $recaptcha = $request->input('recaptcha');

    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => config('services.recaptcha.secret_key'),
        'response' => $recaptcha
    ]);

    return response()->json([
        'success' => $response->json()['success']
    ]);
});

Route::get('/terms/{type}', [TermsController::class, 'getTerms'])->name('terms.get');

Route::get('/api/terms/{type}', function ($type) {
    $terms = \App\Models\TermsAndConditions::where('type', $type)->first();
    return response()->json($terms);
});

Route::get('/terms/info/{section?}', [TermsController::class, 'getInfoContent'])->name('terms.info');
