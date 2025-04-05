<?php

namespace App\Observers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     */
    public function created(Report $report): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Report '{$report->title}' Created")
            ->body("A new report titled '{$report->title}' has been successfully created.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Report '{$report->title}' Updated")
            ->body("The report '{$report->title}' has been successfully updated.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Report '{$report->title}' Deleted")
            ->body("The report '{$report->title}' has been successfully deleted.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Report "restored" event.
     */
    public function restored(Report $report): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Report '{$report->title}' Restored")
            ->body("The report '{$report->title}' has been successfully restored.")
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the Report "force deleted" event.
     */
    public function forceDeleted(Report $report): void
    {
        $recepient = Auth::user();

        Notification::make()
            ->title("Report '{$report->title}' Permanently Deleted")
            ->body("The report '{$report->title}' has been permanently deleted.")
            ->sendToDatabase($recepient);
    }
}
