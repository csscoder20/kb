<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmailSettings;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmailSettingResource\Pages;
use App\Filament\Resources\EmailSettingResource\RelationManagers;

class EmailSettingResource extends Resource
{
    protected static ?string $model = EmailSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'Email';
    protected static ?string $navigationGroup = 'Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form Default
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('driver')
                    ->options([
                        'mailgun' => 'Mailgun',
                        'smtp' => 'SMTP',
                    ])
                    ->required()
                    ->reactive(), // Tambahkan reactive agar bisa mendeteksi perubahan pilihan driver

                // Form untuk Mailgun
                Forms\Components\TextInput::make('secret_key')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn($get) => $get('driver') === 'mailgun'),

                Forms\Components\TextInput::make('domain')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn($get) => $get('driver') === 'mailgun'),

                Forms\Components\Select::make('region')
                    ->options([
                        'US' => 'United States',
                        'EU' => 'Europe',
                        'Asia' => 'Asia',
                        'AU' => 'Australia',
                    ])
                    ->default('US')
                    ->required()
                    ->visible(fn($get) => $get('driver') === 'mailgun'),

                // Form untuk SMTP
                Forms\Components\TextInput::make('host')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn($get) => $get('driver') === 'smtp'),

                Forms\Components\Select::make('encryption')
                    ->options([
                        'ssl' => 'SSL',
                        'tls' => 'TLS',
                        'none' => 'None',
                    ])
                    ->default('tls')
                    ->required()
                    ->visible(fn($get) => $get('driver') === 'smtp'),

                Forms\Components\TextInput::make('port')
                    ->required()
                    ->numeric()
                    ->visible(fn($get) => $get('driver') === 'smtp'),

                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn($get) => $get('driver') === 'smtp'),

                Forms\Components\TextInput::make('password')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn($get) => $get('driver') === 'smtp'),

                // Status ditempatkan di bagian paling bawah
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->columnSpanFull()
                    ->required(),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region'),
                Tables\Columns\TextColumn::make('host')
                    ->searchable(),
                Tables\Columns\TextColumn::make('port')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('encryption'),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListEmailSettings::route('/'),
            'create' => Pages\CreateEmailSetting::route('/create'),
            'view' => Pages\ViewEmailSetting::route('/{record}'),
            'edit' => Pages\EditEmailSetting::route('/{record}/edit'),
        ];
    }
}
