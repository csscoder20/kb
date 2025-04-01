<?php

namespace App\Filament\Resources\BasicResource\Pages;

use App\Filament\Resources\BasicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBasic extends EditRecord
{
    protected static string $resource = BasicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
