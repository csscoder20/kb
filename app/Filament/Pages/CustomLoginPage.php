<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login;

class CustomLoginPage extends Login
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.custom-login';
}
