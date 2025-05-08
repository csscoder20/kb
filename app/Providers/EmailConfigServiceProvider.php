<?php

namespace App\Providers;

use App\Models\EmailSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class EmailConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            // Ambil pengaturan email dari database
            $emailSettings = EmailSettings::getAllAsArray();

            // Jika pengaturan email ada dan diaktifkan
            if (
                !empty($emailSettings) && isset($emailSettings['status']) &&
                ($emailSettings['status'] === true || $emailSettings['status'] === '1' || $emailSettings['status'] === 1)
            ) {
                // Konfigurasi mailer berdasarkan driver
                if ($emailSettings['driver'] === 'smtp') {
                    Config::set('mail.default', 'smtp');
                    Config::set('mail.mailers.smtp.host', $emailSettings['host'] ?? '');
                    Config::set('mail.mailers.smtp.port', $emailSettings['port'] ?? '');
                    Config::set('mail.mailers.smtp.encryption', $emailSettings['encryption'] ?? 'tls');
                    Config::set('mail.mailers.smtp.username', $emailSettings['username'] ?? '');
                    Config::set('mail.mailers.smtp.password', $emailSettings['password'] ?? '');
                } elseif ($emailSettings['driver'] === 'mailgun') {
                    Config::set('mail.default', 'mailgun');
                    Config::set('services.mailgun.domain', $emailSettings['domain'] ?? '');
                    Config::set('services.mailgun.secret', $emailSettings['secret_key'] ?? '');
                    Config::set('services.mailgun.endpoint', $emailSettings['region'] === 'EU' ? 'api.eu.mailgun.net' : 'api.mailgun.net');
                }

                // Set pengirim email
                Config::set('mail.from.address', $emailSettings['email'] ?? 'noreply@example.com');
                Config::set('mail.from.name', $emailSettings['name'] ?? 'System');

                // Log konfigurasi email yang digunakan (tanpa password)
                $logConfig = array_merge(
                    ['driver' => Config::get('mail.default')],
                    ['from' => Config::get('mail.from')]
                );
                logger()->info('Email configuration loaded:', $logConfig);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan crash aplikasi
            logger()->error('Failed to load email configuration: ' . $e->getMessage());
        }
    }
}
