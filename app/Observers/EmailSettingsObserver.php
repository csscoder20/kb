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
        $changes = $emailSettings->getChanges();
        $original = $emailSettings->getOriginal();

        $fieldsToCheck = [
            'name'  => 'Sender Name',
            'email'  => 'Email Address',
            'driver'  => 'Driver',
            'secret_key'  => 'Secret Key',
            'domain'  => 'Domain',
            'region'  => 'Region',
            'host'  => 'Host',
            'port'  => 'Port',
            'encryption'  => 'Encryption',
            'username'  => 'Username',
            'password'  => 'Password',

        ];

        $messages = [];

        foreach ($fieldsToCheck as $field => $label) {
            if (array_key_exists($field, $changes)) {
                $old = $original[$field] ?? '(kosong)';
                $new = $changes[$field] ?? '(kosong)';
                $messages[] = "{$label} has updated from \"{$old}\" to \"{$new}\"";
            }
        }

        if (!empty($messages)) {
            Notification::make()
                ->title("Email Setting '{$emailSettings->title}' Updated")
                ->body(implode("\n", $messages))
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
