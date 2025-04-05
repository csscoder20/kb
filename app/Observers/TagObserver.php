<?php

namespace App\Observers;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Tag '{$tag->name}' Created")
            ->body("A new tag named '{$tag->name}' has been successfully created.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Tag '{$tag->name}' Updated")
            ->body("The tag '{$tag->name}' has been successfully updated.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Tag '{$tag->name}' Deleted")
            ->body("The tag '{$tag->name}' has been successfully deleted.")
            ->sendToDatabase($recepient);
    }
    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        //
    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        //
    }
}
