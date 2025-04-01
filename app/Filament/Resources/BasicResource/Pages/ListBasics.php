<?php

namespace App\Filament\Resources\BasicResource\Pages;

use App\Filament\Resources\BasicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBasics extends ListRecords
{
    protected static string $resource = BasicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
