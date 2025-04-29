<?php

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;

class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    protected static bool $shouldQueue = true;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your customer export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public static function getCompletedNotification(Export $export): Notification
    {
        $user = Auth::user();

        return Notification::make()
            ->success()
            ->title('Export Completed')
            ->body("Successfully exported {$export->successful_rows} customers")
            ->actions([
                Action::make('download')
                    ->label('Download Export')
                    ->url(url('storage/filament_exports/' . $export->file_name), shouldOpenInNewTab: true)
            ])
            ->sendToDatabase($user)
            ->persistent();
    }

    public static function getFailedNotification(Export $export): Notification
    {
        $user = Auth::user();

        return Notification::make()
            ->danger()
            ->title('Export Failed')
            ->body('The export process has failed.')
            ->sendToDatabase($user)
            ->persistent();
    }
}
