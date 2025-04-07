<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use App\Models\Basic;
use Filament\Notifications\Notification;

class Basicsetting extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static string $view = 'filament.pages.basicsetting';
    protected static ?string $navigationLabel = 'Basic';
    protected static ?string $modelLabel = 'Basic';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Basic';

    public ?array $data = [];


    public function mount()
    {
        $this->form->fill(Basic::getAllAsArray());
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
                            ->maxLength(255),

                        TextInput::make('description')
                            ->required(),
                    ])->columns(2),
                Section::make('Text Setting')
                    ->description('Set your own text in the field below.')
                    ->schema([
                        TextInput::make('alert')
                            ->label('Global Alert Text')
                            ->required(),

                        TextInput::make('text_available')
                            ->label('Text for Available Status')
                            ->required(),

                        TextInput::make('text_unavailable')
                            ->label('Text for Unavailable Status')
                            ->required(),

                        TextInput::make('pdf_unavailable')
                            ->label('Message for PDF Unavailable')
                            ->required(),
                        TextInput::make('footer')
                            ->label('Footer Text')
                            ->required(),
                        TextInput::make('created_by')
                            ->label('Created By')
                            ->required(),
                    ])->columns(2),

                Section::make('Theme Settings')
                    ->description("Customize your forum's colors and logos.")
                    ->schema([
                        FileUpload::make('logo_dark')
                            ->label('Dark Mode Logo')
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

                        FileUpload::make('logo_light')
                            ->label('Light Mode Logo')
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

                        ColorPicker::make('dark_color')
                            ->label('Dark Mode Background Color')
                            ->default('#ecf0f6')
                            ->required(),

                        ColorPicker::make('light_color')
                            ->label('Light Mode Background Color')
                            ->default('#020617')
                            ->required(),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();
        Basic::setBulk($data);

        Notification::make()
            ->title('Settings Updated')
            ->body('Basic settings have been successfully updated.')
            ->success()
            ->send();
    }
}
