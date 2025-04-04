<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BasicResource\Pages;
use App\Filament\Resources\BasicResource\RelationManagers;
use App\Models\Basic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BasicResource extends Resource
{
    protected static ?string $model = Basic::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $modelLabel = 'Basic';
    protected static ?string $navigationGroup = 'Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Forum Identity')
                    ->description('Set your forum title and description in the field bellow. Enter a short sentence or two that describes your community. This will appear in the meta tag and show up in search engines.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Welcom Banner')
                    ->description('Configure the text that displays in the banner on the All Discussions page. Use this to welcome guests to your forum.')
                    ->schema([
                        Forms\Components\TextInput::make('banner')
                            ->required()
                            ->label('Banner Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('banner_description')
                            ->required()
                            ->columnSpanFull(),

                    ])->columns(2),

                Forms\Components\Section::make('Forum Settings')
                    ->description("Customize your forum's colors, logos, and other variables.")
                    ->schema([
                        Forms\Components\Select::make('homepage')
                            ->options([
                                'all_discussions' => 'All Discussions',
                                'tags' => 'Tags',
                            ])
                            ->default('tags')
                            ->required(),

                        Forms\Components\ColorPicker::make('color')
                            ->required()
                            ->default('#ecf0f6')
                            ->live(),

                        Forms\Components\Select::make('is_darkmode_active')
                            ->options([
                                'yes' => 'Yes',
                                'no' => 'No',
                            ])
                            ->default('no')
                            ->required(),

                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->nullable(),

                        Forms\Components\FileUpload::make('favicon')
                            ->image()
                            ->nullable(),

                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('banner')
                    ->searchable(),
                Tables\Columns\TextColumn::make('homepage'),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_darkmode_active'),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('favicon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBasics::route('/'),
            'create' => Pages\CreateBasic::route('/create'),
            'view' => Pages\ViewBasic::route('/{record}'),
            'edit' => Pages\EditBasic::route('/{record}/edit'),
        ];
    }
}
