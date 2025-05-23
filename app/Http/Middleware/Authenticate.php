<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('admin*')) {
            return route('filament.admin.auth.login');
        }

        return route('filament.admin.auth.login');
    }
}
