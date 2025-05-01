<?php

namespace App\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Spatie\Activitylog\Models\Activity;

class ActivityExporter extends Exporter
{
    protected static ?string $model = Activity::class;

    protected static bool $shouldQueue = true;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('log_name')
                ->label('Type'),
            ExportColumn::make('event')
                ->label('Event'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('subject_type')
                ->label('Subject'),
            ExportColumn::make('causer.name')
                ->label('User'),
            ExportColumn::make('created_at')
                ->label('Logged At'),
        ];
    }
}
