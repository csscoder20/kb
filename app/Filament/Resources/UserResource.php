<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Infolists\Components\ImageEntry;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $modelLabel = 'Users List';
    protected static ?string $navigationGroup = 'User Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 5 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->options(
                        \Spatie\Permission\Models\Role::all()->pluck('name', 'name')
                    )
                    ->searchable()
                    ->required()
                    ->default('guest')
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state($record->roles->pluck('name')->first());
                        }
                    })
                    ->dehydrated(true),
                Forms\Components\FileUpload::make('profile_picture')
                    ->image()
                    ->directory('profile-pictures')
                    ->imageEditor()
                    ->previewable()
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->circular()
                    ->disableClick()
                    ->defaultImageUrl(url('/default-avatar.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Lengkap')
                    ->disableClick(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->disableClick()
                    ->copyable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disableClick(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disableClick(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'superadmin' => 'danger',
                        'admin' => 'warning',
                        'guest' => 'gray',
                        default => 'gray',
                    }),

            ])->emptyStateDescription('Once you write your first post, it will appear here.')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->closeModalByClickingAway(false)->closeModalByEscaping(false),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('User Deleted')
                            ->body('The User data has successfully deleted.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Details')
                    ->schema([
                        ImageEntry::make('profile_picture')
                            ->label('Profile Picture')
                            ->circular()
                            ->hiddenLabel(),
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('roles.name')
                            ->label('Role')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'super_admin' => 'danger',
                                'superadmin' => 'danger',
                                'admin' => 'warning',
                                'guest' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('email_verified_at'),
                        TextEntry::make('password')->columnSpanFull(),
                    ])->columns(3)
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['roles']);
        return $data;
    }
}
