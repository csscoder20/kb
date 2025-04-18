<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;

class CustomLoginPage extends BaseLogin
{
    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             TextInput::make('email')
    //                 ->label('Email')
    //                 ->email()
    //                 ->required(),
    //             TextInput::make('password')
    //                 ->label('Password')
    //                 ->password()
    //                 ->required(),
    //         ])
    //         ->extraActions([
    //             Action::make('loginWithGoogle')
    //                 ->label('Login with Google')
    //                 ->url(route('google.login'))
    //                 ->color('danger')
    //                 ->icon('heroicon-o-globe-alt'),
    //         ]);
    // }
}
