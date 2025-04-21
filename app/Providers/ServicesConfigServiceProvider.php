<?php

namespace App\Providers;

use App\Models\Basic;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class ServicesConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            // Load all settings at once to minimize database queries
            $settings = Basic::getAllAsArray();

            // Google configuration
            Config::set('services.google.client_id', $settings['google_client_id'] ?? null);
            Config::set('services.google.client_secret', $settings['google_client_secret'] ?? null);
            Config::set('services.google.redirect', $settings['google_redirect_uri'] ?? null);

            // Microsoft configuration
            Config::set('services.microsoft.client_id', $settings['microsoft_client_id'] ?? null);
            Config::set('services.microsoft.client_secret', $settings['microsoft_client_secret'] ?? null);
            Config::set('services.microsoft.redirect', $settings['microsoft_redirect_uri'] ?? null);
            Config::set('services.microsoft.tenant', 'common');

            // reCAPTCHA configuration
            Config::set('services.recaptcha.site_key', $settings['recaptcha_site_key'] ?? null);
            Config::set('services.recaptcha.secret_key', $settings['recaptcha_secret_key'] ?? null);
        } catch (\Exception $e) {
            // Log error but don't crash the application
            logger()->error('Failed to load services configuration: ' . $e->getMessage());
        }
    }
}
