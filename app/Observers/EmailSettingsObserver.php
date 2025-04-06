<?php

namespace App\Observers;

use App\Models\EmailSettings;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EmailSettingsObserver
{
    /**
     * Handle the EmailSettings "created" event.
     */
    public function created(EmailSettings $emailSettings): void
    {
        //
    }

    /**
     * Handle the EmailSettings "updated" event.
     */
    public function updated(EmailSettings $emailSettings): void
    {
        $recepient = Auth::user();

        $old = $emailSettings->getOriginal('value');
        $new = $emailSettings->value;
        $key = $emailSettings->key;

        // Daftar label lebih ramah pengguna
        $labels = [
            'name'        => 'Sender Name',
            'email'       => 'Email Address',
            'driver'      => 'Mail Driver',
            'secret_key'  => 'Secret Key',
            'domain'      => 'Domain',
            'region'      => 'Region',
            'host'        => 'Mail Host',
            'port'        => 'Mail Port',
            'encryption'  => 'Encryption',
            'username'    => 'Username',
            'password'    => 'Password',
        ];

        // Field yang bersifat sensitif
        $sensitiveFields = ['password', 'secret_key'];

        $label = $labels[$key] ?? $key;

        if ($old !== $new) {
            $oldDisplay = in_array($key, $sensitiveFields) ? '***' : $old;
            $newDisplay = in_array($key, $sensitiveFields) ? '***' : $new;

            Notification::make()
                ->title("Email Setting Updated")
                ->body("Email Setting {$label} has changed from \"{$oldDisplay}\" to \"{$newDisplay}\".")
                ->sendToDatabase($recepient);
        }
    }

    /**
     * Handle the EmailSettings "deleted" event.
     */
    public function deleted(EmailSettings $emailSettings): void
    {
        //
    }

    /**
     * Handle the EmailSettings "restored" event.
     */
    public function restored(EmailSettings $emailSettings): void
    {
        //
    }

    /**
     * Handle the EmailSettings "force deleted" event.
     */
    public function forceDeleted(EmailSettings $emailSettings): void
    {
        //
    }
}
