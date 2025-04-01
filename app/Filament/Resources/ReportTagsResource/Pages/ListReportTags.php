<?php

namespace App\Filament\Resources\ReportTagsResource\Pages;

use App\Filament\Resources\ReportTagsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportTags extends ListRecords
{
    protected static string $resource = ReportTagsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
