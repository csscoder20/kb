<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportTagsResource\Pages;
use App\Filament\Resources\ReportTagsResource\RelationManagers;
use App\Models\ReportTags;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportTagsResource extends Resource
{
    protected static ?string $model = ReportTags::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $modelLabel = 'Posts Tags';
    protected static ?string $navigationGroup = 'Report Management';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 5 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tag_id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportTags::route('/'),
            'create' => Pages\CreateReportTags::route('/create'),
            'view' => Pages\ViewReportTags::route('/{record}'),
            'edit' => Pages\EditReportTags::route('/{record}/edit'),
        ];
    }
}
