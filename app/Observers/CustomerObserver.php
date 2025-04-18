<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Customer '{$customer->name}' Created")
            ->body("A new Customer named '{$customer->name}' has been successfully created.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Customer '{$customer->name}' Updated")
            ->body("The Customer '{$customer->name}' has been successfully updated.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Customer '{$customer->name}' Deleted")
            ->body("The Customer '{$customer->name}' has been successfully deleted.")
            ->sendToDatabase($recepient);
    }
    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
