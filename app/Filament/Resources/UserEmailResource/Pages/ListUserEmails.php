<?php

namespace App\Filament\Resources\UserEmailResource\Pages;

use App\Filament\Resources\UserEmailResource;
use Filament\Resources\Pages\ListRecords;

class ListUserEmails extends ListRecords
{
    protected static string $resource = UserEmailResource::class;
}
