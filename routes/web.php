<?php

// use App\Models\User;
// use Filament\Filament;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\RegisterController;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Route::get('/', function () {
//     return view('chat');
// });


Route::get('/', [ChatController::class, 'showForm']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/config', [ConfigController::class, 'getConfig']);
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/ask', [ChatController::class, 'ask'])->name('tag.ask');


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

// Route Customer
Route::get('/customers/select', [CustomerController::class, 'select']);





// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// })->name('google.login');


// Route::get('/auth/google/callback', function () {
//     $googleUser = Socialite::driver('google')->stateless()->user();

//     $user = User::updateOrCreate(
//         ['email' => $googleUser->getEmail()],
//         [
//             'name' => $googleUser->getName(),
//             'google_id' => $googleUser->getId(),
//             'password' => bcrypt(Str::random(16)), // kasih password random
//         ]
//     );

//     Auth::login($user);

//     return redirect('/admin');
// });




// Route::get('/auth/microsoft', function () {
//     return Socialite::driver('microsoft')->redirect();
// })->name('login.microsoft');

// Route::get('/auth/microsoft/callback', function () {
//     $microsoftUser = Socialite::driver('microsoft')->user();

//     $user = User::updateOrCreate(
//         ['email' => $microsoftUser->getEmail()],
//         [
//             'name' => $microsoftUser->getName(),
//             'password' => bcrypt(Str::random(16)), // tambahan
//         ]
//     );

//     Auth::login($user);

//     // return redirect()->route('filament.pages.dashboard');
//     return redirect('/admin');
// });


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

    Auth::login($user);

    return redirect('/admin');
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
