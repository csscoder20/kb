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
        $recipient = Auth::user();

        if (!$recipient) {
            return; // Tidak kirim notifikasi jika tidak ada user yang login
        }

        Notification::make()
            ->success() // Tambahkan status notifikasi
            ->title("Customer '{$customer->name}' Created")
            ->body("A new Customer named '{$customer->name}' has been successfully created.")
            ->sendToDatabase($recipient)
            ->persistent(); // Membuat notifikasi tetap ada sampai user menutupnya
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        $recipient = Auth::user();

        if (!$recipient) {
            return;
        }

        Notification::make()
            ->success()
            ->title("Customer '{$customer->name}' Updated")
            ->body("The Customer '{$customer->name}' has been successfully updated.")
            ->sendToDatabase($recipient)
            ->persistent();
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        $recipient = Auth::user();

        if (!$recipient) {
            return;
        }

        Notification::make()
            ->success()
            ->title("Customer '{$customer->name}' Deleted")
            ->body("The Customer '{$customer->name}' has been successfully deleted.")
            ->sendToDatabase($recipient)
            ->persistent();
    }
    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        $recipient = Auth::user();

        if (!$recipient) {
            return;
        }

        Notification::make()
            ->success()
            ->title("Customer '{$customer->name}' Restored")
            ->body("The Customer '{$customer->name}' has been successfully restored.")
            ->sendToDatabase($recipient)
            ->persistent();
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        $recipient = Auth::user();

        if (!$recipient) {
            return;
        }

        Notification::make()
            ->success()
            ->title("Customer '{$customer->name}' Permanently Deleted")
            ->body("The Customer '{$customer->name}' has been permanently deleted.")
            ->sendToDatabase($recipient)
            ->persistent();
    }
}
