<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("User {$user->name} Created")
            ->body("A new user named {$user->name} has been successfully created.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {

        $recepient = Auth::user();
        $changes = $user->getChanges();
        $original = $user->getOriginal();

        $fieldsToCheck = [
            'name'   => 'Full Name',
            'email'   => 'Email Address',
            'profile_picture'   => 'Profile Picture',
            'role'   => 'User Role',
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
                ->title("User '{$user->name}' Updated")
                ->body(implode("\n", $messages))
                ->sendToDatabase($recepient);
        }
    }
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("User {$user->name} Deleted")
            ->body("The user {$user->name} has been successfully deleted.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
