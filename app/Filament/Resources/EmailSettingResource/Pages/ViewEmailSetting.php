<?php

namespace App\Filament\Resources\EmailSettingResource\Pages;

use App\Filament\Resources\EmailSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmailSetting extends ViewRecord
{
    protected static string $resource = EmailSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
