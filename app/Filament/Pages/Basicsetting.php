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
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions\Action;

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


    public function mount(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 204);

        // Load all settings from database
        $this->form->fill(Basic::getAllAsArray());
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

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Theme')
                        ->icon('heroicon-m-paint-brush')
                        ->completedIcon('heroicon-m-paint-brush')
                        ->description('Set the forum title, description, logo, and colors.')
                        ->schema([
                            Section::make('Forum Identity')
                                ->description('Set your forum title, description, and logos in the field below.')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('description')
                                        ->required(),
                                    TextInput::make('footer')
                                        ->label('Footer Text')
                                        ->required(),
                                    TextInput::make('created_by')
                                        ->label('Credit Footer')
                                        ->required(),
                                    FileUpload::make('logo_light')
                                        ->label('Light Mode Logo')
                                        ->image()
                                        ->previewable(true)
                                        ->imagePreviewHeight('200')
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
                                        ->imagePreviewHeight('200')
                                        ->loadingIndicatorPosition('left')
                                        ->panelAspectRatio('2:1')
                                        ->panelLayout('integrated')
                                        ->removeUploadedFileButtonPosition('right')
                                        ->uploadButtonPosition('left')
                                        ->uploadProgressIndicatorPosition('left')
                                        ->nullable(),
                                ])->columns(2),
                        ]),

                    Wizard\Step::make('API Settings')
                        ->description('Recapatcha and Google & Microsoft Login')
                        ->icon('heroicon-m-cog')
                        ->completedIcon('heroicon-m-cog')
                        ->schema([
                            Section::make('Google API Settings')
                                ->description('Set your own API data in the field below.')
                                ->schema([
                                    TextInput::make('google_client_id')
                                        ->label('Google Client ID')
                                        ->required(),

                                    TextInput::make('google_client_secret')
                                        ->label('Google Client Secret')
                                        ->required(),

                                    TextInput::make('google_redirect_uri')
                                        ->label('Google Redirect URI')
                                        ->required(),
                                ])->columns(2),
                            Section::make('Microsoft Azure API Settings')
                                ->description('Set your own API data in the field below.')
                                ->schema([
                                    TextInput::make('microsoft_client_id')
                                        ->label('Microsoft Client ID')
                                        ->required(),
                                    TextInput::make('microsoft_client_secret')
                                        ->label('Microsoft Client Secret')
                                        ->required(),
                                    TextInput::make('microsoft_redirect_uri')
                                        ->label('Microsoft Redirect URI')
                                        ->required(),
                                ])->columns(2),
                            Section::make('Google reCAPTCHA Settings')
                                ->description('Set your own API data in the field below.')
                                ->schema([
                                    TextInput::make('recaptcha_site_key')
                                        ->label('Google Recaptcha Site Key')
                                        ->required(),
                                    TextInput::make('recaptcha_secret_key')
                                        ->label('Google Recaptcha Secret Key')
                                        ->required(),
                                ])->columns(2),
                        ]),
                    Wizard\Step::make('Text Settings')
                        ->description('Change Text with your own')
                        ->icon('heroicon-m-document-text')
                        ->completedIcon('heroicon-m-document-text')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
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
                                ])->columns(2),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->startOnStep(1)
                    ->nextAction(fn(Action $action): Action => $action->extraAttributes(['x-show' => 'false']))
                    ->previousAction(fn(Action $action): Action => $action->extraAttributes(['x-show' => 'false']))
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
