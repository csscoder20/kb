<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLoginPage extends BaseLogin
{
    // Kamu bisa override form() di sini kalau mau custom field, contoh:
    /*
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')->label('Email / Username')->required(),
                TextInput::make('password')->password()->required(),
                Checkbox::make('remember'),
            ]);
    }
    */
}
