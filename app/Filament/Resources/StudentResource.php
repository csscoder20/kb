<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Student;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;


use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Notifications\Notification;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Student';
    protected static ?string $modelLabel = 'Students List';
    protected static ?string $navigationGroup = 'Students Management';
    protected static ?string $recordTitleAttribute = 'first_name';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->last_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'midle_name',
            'last_name',
            'country.name'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Country' => $record->country->name
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['country']);
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
                Forms\Components\Section::make('Students Identity')
                    ->description('Pull the user identity in the field bellow!')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('midle_name')
                            ->required()
                            ->label('Midle Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->label('Last Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->required()
                            ->label('Place of Birth')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->label('Date of Birth'),
                    ])->columns(3),
                Forms\Components\Section::make('Students Address')
                    ->description('Pull the user identity in the field bellow!')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->required()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->searchable(),
                        Forms\Components\Select::make('state_id')
                            ->options(fn(Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('city_id')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->required()
                            ->preload()
                            ->live()
                            ->searchable(),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->label('Full Address')
                            ->maxLength(255),
                        // ->columnSpanFull()
                        // ->columns(2),
                        Forms\Components\TextInput::make('zip_code')
                            ->required()
                            ->label('ZIP Code')
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Section::make('Additional Information')
                    ->description('Fill the Additional informations in the field bellow!')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->relationship(name: 'department', titleAttribute: 'name')
                            ->required()
                            ->preload()
                            ->label('Department Name')
                            ->searchable(),
                        Forms\Components\DatePicker::make('date_hired')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->label('Date of Admission'),
                        // ->columnSpanFull(),
                    ])->columns(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('midle_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('place_of_birth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->emptyStateDescription('Once you write your first post, it will appear here.')
            ->filters([
                SelectFilter::make('Department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Department')
                    ->indicator('Department'),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->label('From Date'),
                        DatePicker::make('created_until')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),
            ])
            // ->filters([
            //     SelectFilter::make('Department')
            //         ->relationship('department', 'name')
            //         ->searchable()
            //         ->preload()
            //         ->label('Filter by Department')
            //         ->indicator('Department'),

            //     Filter::make('created_at')
            //         ->form([
            //             DatePicker::make('created_from')
            //                 ->native(false)
            //                 ->displayFormat('d/m/Y')
            //                 ->label('From Date'),
            //             DatePicker::make('created_until')
            //                 ->native(false)
            //                 ->displayFormat('d/m/Y')
            //                 ->label('To Date'),
            //         ])
            //         ->query(function (Builder $query, array $data): Builder {
            //             return $query
            //                 ->when(
            //                     $data['created_from'],
            //                     fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            //                 )
            //                 ->when(
            //                     $data['created_until'],
            //                     fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            //                 );
            //         })

            //         ->indicateUsing(function (array $data): array {
            //             $indicators = [];

            //             if ($data['created_from'] ?? null) {
            //                 $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
            //                     ->removeField('created_from');
            //             }

            //             if ($data['created_until'] ?? null) {
            //                 $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
            //                     ->removeField('created_until');
            //             }

            //             return $indicators;
            //         })->columnSpan(2)->columns(2)
            // ], layout: FiltersLayout::AboveContent)->filtersFormColumns(3)

            ->actions([
                Tables\Actions\ViewAction::make()->closeModalByClickingAway(false)->closeModalByEscaping(false),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Student Deleted')
                            ->body('The Student data has successfully deleted.')
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
                Section::make('Student Details')
                    ->schema([
                        TextEntry::make('country.name'),
                        TextEntry::make('state.name'),
                        TextEntry::make('city.name'),
                        TextEntry::make('department.name'),
                    ])->columns(3),
                Section::make('Student Identity')
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('midle_name'),
                        TextEntry::make('last_name'),
                    ])->columns(3),
                Section::make('Student Address')
                    ->schema([
                        TextEntry::make('address'),
                        TextEntry::make('zip_code'),
                    ])->columns(2)
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            // 'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
