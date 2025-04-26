<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Models\Basic;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Fieldset;

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
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Theme Settings')
                            ->icon('heroicon-m-paint-brush')
                            ->schema([
                                Fieldset::make('Forum Identity')
                                    // ->description('Set your forum title and description in the field below.')
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
                                    ])->columns(2),
                                Fieldset::make('Logos')
                                    // ->description('Set your forum logos in the field below.')
                                    ->schema([
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
                                    ])->columns(4),
                            ]),
                        Tabs\Tab::make('API Settings')
                            ->icon('heroicon-m-cog')
                            ->schema([
                                Fieldset::make('Google API Settings')
                                    // ->description('Set your own API data in the field below.')
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
                                Fieldset::make('Microsoft Azure API Settings')
                                    // ->description('Set your own API data in the field below.')
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
                                Fieldset::make('Google reCAPTCHA Settings')
                                    // ->description('Set your own API data in the field below.')
                                    ->schema([
                                        TextInput::make('recaptcha_site_key')
                                            ->label('Google Recaptcha Site Key')
                                            ->required(),
                                        TextInput::make('recaptcha_secret_key')
                                            ->label('Google Recaptcha Secret Key')
                                            ->required(),
                                    ])->columns(2),
                            ]),
                        Tabs\Tab::make('Quick-Search')
                            ->icon('heroicon-m-magnifying-glass')
                            ->schema([
                                Fieldset::make('Quick-Search Page')
                                    // ->description('Set your own text in the field below.')
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
                        Tabs\Tab::make('New-Post')
                            ->icon('heroicon-m-pencil-square')
                            ->schema([
                                Fieldset::make('New-Post Page')
                                    ->schema([
                                        TextInput::make('alert_title')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('alert_title_description')
                                            ->required(),
                                        TextInput::make('post_title')
                                            ->required(),
                                        TextInput::make('form_title')
                                            ->required(),
                                        TextInput::make('form_technology')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('form_customer')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('form_description')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('form_file')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('form_file_alert')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('report_success_created')
                                            ->required()
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),
                        Tabs\Tab::make('All-Posts')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                Fieldset::make('Preview Modal Policy')
                                    ->schema([
                                        TextInput::make('preview_modal_title')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('preview_modal_description')
                                            ->required(),
                                        TextInput::make('preview_checkbox_text')
                                            ->required(),
                                        TextInput::make('preview_recaptcha_alert')
                                            ->required(),
                                    ])->columns(2),
                                Fieldset::make('Download Modal Policy')
                                    ->schema([
                                        TextInput::make('download_modal_title')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('download_modal_description')
                                            ->required(),
                                        TextInput::make('download_checkbox_text')
                                            ->required(),
                                        TextInput::make('download_recaptcha_alert')
                                            ->required(),
                                    ])->columns(2),
                            ]),
                    ])
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
