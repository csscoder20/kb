<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Models\Basic;
use App\Models\Tag;
use App\Http\Responses\LogoutResponse;
use App\Http\Responses\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $basics = cache()->remember('basic_config', 60, function () {
                return Basic::all()->pluck('value', 'key')->toArray();
            });

            $view->with('basicConfig', $basics);
        });

        View::composer('layouts.app', function ($view) {
            $basics = Basic::getAllAsArray();

            $tagData = null;
            if (Request::is('allposts') && Request::has('slug')) {
                $slug = Request::query('slug');
                $tagData = Tag::where('slug', $slug)->first();
            }

            $view->with(compact('basics', 'tagData'));
        });
    }
}
