<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Info';
    protected static ?string $modelLabel = 'Info';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Info';

    public function mount()
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 204);
    }

    // Jika si Mas bukan super_admin, di panel si Mas, menu ini gak tampil
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        $query = static::getModel()::query();

        if (!$user->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $user = Auth::user();

        $query = static::getModel()::query();

        if (!$user->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->count() > 5 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'info' => 'Info',
                        'warning' => 'Warning',
                        'success' => 'Success',
                        'error' => 'Error',
                        'question' => 'Question',
                    ]),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->default(true)
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->default(true)
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\Toggle::make('show_once_per_session')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\BadgeColumn::make('type'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_date')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
                Tables\Columns\TextColumn::make('end_date')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
