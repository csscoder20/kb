<?php

namespace App\Filament\Resources\CountryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CountryResource;

class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Country Updated')
            ->body('The Country data has successfully updated.');
    }
}
