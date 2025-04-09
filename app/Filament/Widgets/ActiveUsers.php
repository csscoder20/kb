<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Tables;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveUsers extends BaseWidget
{
    protected static ?string $heading = 'Pengguna Aktif Saat Ini';
    protected static ?int $sort = 6;

    protected function getTableQuery(): Builder
    {
        // Ambil user_id dari session aktif (10 menit terakhir)
        $activeUserIds = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(10)->timestamp)
            ->pluck('user_id')
            ->filter()
            ->unique();

        return User::query()->whereIn('id', $activeUserIds);
    }

    public function getTableRecordsPerPage(): int
    {
        return 5;
    }

    public function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Nama'),
            Tables\Columns\TextColumn::make('email')->label('Email'),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                ->badge()
                ->color(fn($state) => match ($state) {
                    'super_admin' => 'danger',
                    'admin' => 'warning',
                    'guest' => 'gray',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Login')->since(),
        ];
    }
}
