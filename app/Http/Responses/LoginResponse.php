<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Jika ada intended URL, gunakan itu
        if ($intendedUrl = session()->get('url.intended')) {
            return redirect()->intended($intendedUrl);
        }

        // Jika tidak, gunakan logic default
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/');
    }
}
