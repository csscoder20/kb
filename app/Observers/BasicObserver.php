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
        $changes = $basic->getChanges();
        $original = $basic->getOriginal();

        $fieldsToCheck = [
            'title' => 'Title',
            'description' => 'Description',
            'banner' => 'Banner Title',
            'banner_description' => 'Banner Description',
            'homepage' => 'Homepage',
            'color' => 'Color',
            'is_darkmode_active' => 'Darkmode Status',
            'logo' => 'Logo',
            'favicon' => 'Favicon',
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
                ->title("Basic Setting '{$basic->title}' Updated")
                ->body(implode("\n", $messages))
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
