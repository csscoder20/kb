<?php

namespace App\Observers;

use App\Models\Basic;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class BasicObserver
{
    /**
     * Handle the Basic "created" event.
     */
    public function created(Basic $basic): void
    {
        //
    }

    /**
     * Handle the Basic "updated" event.
     */
    public function updated(Basic $basic): void
    {
        $recepient = Auth::user();

        $old = $basic->getOriginal('value');
        $new = $basic->value;
        $key = $basic->key;

        // Daftar label untuk setiap key
        $labels = [
            'title' => 'Title',
            'description' => 'Description',
            'alert' => 'Global Alert Text',
            'text_available' => 'Available Text',
            'text_unavailable' => 'Unavailable Text',
            'pdf_unavailable' => 'PDF Unavailable Message',
            'footer' => 'Footer Text',
            'logo_dark' => 'Dark Mode Logo',
            'logo_light' => 'Light Mode Logo',
            'favicon' => 'Favicon',
            'dark_color' => 'Dark Mode Background Color',
            'light_color' => 'Light Mode Background Color',
        ];

        $label = $labels[$key] ?? $key;

        if ($old !== $new) {
            Notification::make()
                ->title("Basic Setting Updated")
                ->body("Setting {$label} has changed from \"{$old}\" to \"{$new}\".")
                ->sendToDatabase($recepient);
        }
    }


    /**
     * Handle the Basic "deleted" event.
     */
    public function deleted(Basic $basic): void
    {
        //
    }

    /**
     * Handle the Basic "restored" event.
     */
    public function restored(Basic $basic): void
    {
        //
    }

    /**
     * Handle the Basic "force deleted" event.
     */
    public function forceDeleted(Basic $basic): void
    {
        //
    }
}
