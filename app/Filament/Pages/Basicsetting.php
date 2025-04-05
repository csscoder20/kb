<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use App\Models\Basic;
use Filament\Notifications\Notification;

class Basicsetting extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static string $view = 'filament.pages.basicsetting';
    protected static ?string $navigationLabel = 'Basic Setting';
    protected static ?string $modelLabel = 'Basic Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Basic Setting';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill(
            Basic::first()?->toArray() ?? [] // Ambil data pertama atau array kosong jika belum ada
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Forum Identity')
                    ->description('Set your forum title and description in the field below.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Welcome Banner')
                    ->description('Configure the text that displays in the banner on the All Discussions page.')
                    ->schema([
                        TextInput::make('banner')
                            ->required()
                            ->label('Banner Title')
                            ->maxLength(255),

                        Textarea::make('banner_description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Forum Settings')
                    ->description("Customize your forum's colors, logos, and other variables.")
                    ->schema([
                        Select::make('homepage')
                            ->options([
                                'all_discussions' => 'All Discussions',
                                'tags' => 'Tags',
                            ])
                            ->default('tags')
                            ->required(),

                        ColorPicker::make('color')
                            ->required()
                            ->default('#ecf0f6')
                            ->live(),

                        Select::make('is_darkmode_active')
                            ->options([
                                'yes' => 'Yes',
                                'no' => 'No',
                            ])
                            ->default('no')
                            ->required(),

                        FileUpload::make('logo')
                            ->image()
                            ->previewable(true)
                            ->imagePreviewHeight('250')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->nullable(),

                        FileUpload::make('favicon')
                            ->image()
                            ->previewable(true)
                            ->imagePreviewHeight('250')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->nullable(),

                        // FileUpload::make('logo')
                        //     ->image()
                        //     ->previewable(true)
                        //     ->disk('public') // Sesuaikan dengan disk yang kamu pakai
                        //     ->directory('uploads') // Pastikan sesuai dengan penyimpanan
                        //     ->default(fn() => Basic::first()?->logo) // Menampilkan gambar lama
                        //     ->nullable(),

                        // FileUpload::make('favicon')
                        //     ->image()
                        //     ->previewable(true)
                        //     ->disk('public')
                        //     ->directory('uploads')
                        //     ->default(fn() => Basic::first()?->favicon)
                        //     ->nullable(),

                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        // $basic = Basic::first();
        // if ($basic) {
        //     $basic->update($data);
        // } else {
        //     Basic::create($data);
        // }

        $basic = Basic::first();
        if ($basic) {
            $basic->fill($data)->save(); // ✅ Observer akan terpanggil!
        } else {
            Basic::create($data); // Ini tetap bisa panggil created()
        }


        Notification::make()
            ->title('Settings Updated')
            ->body('Forum setting has been successfully updated.')
            ->success()
            ->send();
    }
}
