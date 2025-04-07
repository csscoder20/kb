<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterSave(): void
    {
        $this->record->syncRoles([$this->data['roles'] ?? 'guest']);
    }

    protected function afterCreate(): void
    {
        $this->record->syncRoles([$this->data['roles'] ?? 'guest']);
    }
}
