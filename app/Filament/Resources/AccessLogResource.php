<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessLogResource\Pages;
use App\Models\AccessLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccessLogResource extends Resource
{
    protected static ?string $model = AccessLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Monitoring';
    protected static ?string $navigationLabel = 'File Access';

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 5 ? 'warning' : 'success';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report.title')
                    ->label('Report')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'view_pdf' => 'danger',
                        'download_word' => 'success',
                        'upload' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('user_ip')
                    ->label('IP Address'),
                Tables\Columns\TextColumn::make('user_agent')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('User Device'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'view_pdf' => 'View PDF',
                        'download_word' => 'Download Word',
                        'upload' => 'Upload File'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccessLogs::route('/'),
        ];
    }
}
