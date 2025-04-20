<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReportResource\Pages;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
use Illuminate\Support\Facades\Auth;
use KoalaFacade\FilamentAlertBox\Forms\Components\AlertBox;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Report Management';

    public static function getNavigationSort(): ?int
    {
        return 1;
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

    public static function beforeCreate(Form $form, Report $record): void
    {
        $record->user_id = Auth::id();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                AlertBox::make()
                    ->label(label: 'Format Penamaan MoP sesuai judul file MoP')
                    ->helperText(text: 'Contoh: Upgrade Panorama and Palo Alto 850 from Version 10.1.11H5 TO 10.1.14H9')
                    ->columnSpanFull()
                    ->info(),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => auth()->id()),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Customer Name')
                            ->required()
                            ->unique('customers', 'name'),
                    ])
                    ->required(),
                Forms\Components\Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->maxItems(2)
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('file')
                    ->directory('reports')
                    ->required()
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        if (str_ends_with($state, '.docx')) {
                            $pdfPath = Report::convertDocxToPdf($state);
                            if ($pdfPath) {
                                $set('pdf_file', $pdfPath);
                            } else {
                            }
                        }
                    }),
                Forms\Components\Hidden::make('pdf_file'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole('super_admin')) {
                    $query->where('user_id', auth()->id());
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->disableClick()
                    ->limit(80)
                    ->copyable()
                    ->tooltip(fn($record) => $record->title),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disableClick(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->disableClick(),
                Tables\Columns\TextColumn::make('user.name')
                    ->badge()
                    ->label('Uploaded by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disableClick(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->disableClick()
                    ->sortable()
                    ->label('Published at')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->disableClick()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->visible(
                        fn($record) =>
                        auth()->user()?->hasRole('super_admin') ||
                            $record->user_id === auth()->id()
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn($record) =>
                        auth()->user()?->hasRole('super_admin') ||
                            $record->user_id === auth()->id()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()?->hasRole('super_admin')),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                PdfViewerEntry::make('pdf_file')
                    ->label('Preview PDF')
                    ->minHeight('100vh')
                    ->columnSpanFull()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Kalau user bukan super_admin, filter hanya report miliknya
        if (!auth()->user()?->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'view' => Pages\ViewReport::route('/{record}'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
