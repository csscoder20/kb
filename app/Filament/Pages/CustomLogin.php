<?php

use Filament\Forms\Components\Actions\Action;
use Filament\Pages\Auth\Login;

class CustomLogin extends Login
{
    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Action::make('loginWithGoogle')
                ->label('Login with Google')
                ->url(route('google.login'))
                ->color('danger')
                ->icon('heroicon-o-globe-alt'),
        ];
    }
}
