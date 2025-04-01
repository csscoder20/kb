<?php

namespace App\Filament\Resources\ReportTagsResource\Pages;

use App\Filament\Resources\ReportTagsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReportTags extends ViewRecord
{
    protected static string $resource = ReportTagsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
