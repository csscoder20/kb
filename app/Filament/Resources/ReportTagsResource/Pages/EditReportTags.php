<?php

namespace App\Filament\Resources\ReportTagsResource\Pages;

use App\Filament\Resources\ReportTagsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportTags extends EditRecord
{
    protected static string $resource = ReportTagsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
