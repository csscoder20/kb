<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestReports extends BaseWidget
{
    protected static ?string $heading = '5 Report Terbaru';
    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        return Report::query()->latest()->limit(5);
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
            Tables\Columns\TextColumn::make('title')->label('Judul'),
            Tables\Columns\ImageColumn::make('user.profile_picture')
                ->circular()
                ->disableClick()
                ->label('Dibuat Oleh')
                ->tooltip(fn($record) => $record->user?->name)
                ->getStateUsing(
                    fn($record) =>
                    $record->user?->profile_picture
                        ?: 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name ?? 'User') . '&color=FFFFFF&background=020617'
                ),
            Tables\Columns\TextColumn::make('status')->badge()->label('Status'),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
        ];
    }

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }
}
