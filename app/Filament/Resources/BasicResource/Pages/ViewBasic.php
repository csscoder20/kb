<?php

namespace App\Filament\Resources\BasicResource\Pages;

use App\Filament\Resources\BasicResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBasic extends ViewRecord
{
    protected static string $resource = BasicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
