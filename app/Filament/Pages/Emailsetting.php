<?php

namespace App\Filament\Pages;

use App\Models\EmailSettings;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;

class Emailsetting extends Page
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.emailsetting';
    protected static ?string $navigationLabel = 'Email Setting';
    protected static ?string $modelLabel = 'Email Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Email Setting';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill(
            EmailSettings::first()?->toArray() ?? [] // Ambil data dari tabel email_settings
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sender Identity')
                    ->description('Set your forum title and description in the field below.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Sender Name')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Select::make('driver')
                            ->label('Mail Driver')
                            ->options([
                                'mailgun' => 'Mailgun',
                                'smtp' => 'SMTP',
                            ])
                            ->default('mailgun')
                            ->reactive()
                            ->required(),
                    ])->columns(3),

                Section::make('Mailgun Settings')
                    ->description('Set your Mailgun settings in the field below.')
                    ->schema([
                        TextInput::make('secret_key')
                            ->required(fn(Get $get) => $get('driver') === 'mailgun')
                            ->maxLength(255),

                        TextInput::make('domain')
                            ->required(fn(Get $get) => $get('driver') === 'mailgun')
                            ->maxLength(255),

                        Select::make('region')
                            ->options([
                                'US' => 'United States',
                                'EU' => 'Europe',
                                'Asia' => 'Asia',
                                'AU' => 'Australia',
                            ])
                            ->default('US')
                            ->required(fn(Get $get) => $get('driver') === 'mailgun'),

                    ])->columns(3)
                    ->visible(fn(Get $get) => $get('driver') === 'mailgun'),


                Section::make('SMTP Settings')
                    ->description('Set your SMTP settings in the field below.')
                    ->schema([
                        TextInput::make('host')
                            ->required(fn(Get $get) => $get('driver') === 'smtp')
                            ->maxLength(255),

                        Select::make('encryption')
                            ->options([
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                                'none' => 'None',
                            ])
                            ->default('tls')
                            ->required(fn(Get $get) => $get('driver') === 'smtp'),

                        TextInput::make('port')
                            ->required(fn(Get $get) => $get('driver') === 'smtp')
                            ->numeric(),

                        TextInput::make('username')
                            ->required(fn(Get $get) => $get('driver') === 'smtp')
                            ->maxLength(255),

                        TextInput::make('password')
                            ->required(fn(Get $get) => $get('driver') === 'smtp')
                            ->password()
                            ->maxLength(255),

                    ])->columns(3)
                    ->visible(fn(Get $get) => $get('driver') === 'smtp'),

                Toggle::make('status')
                    ->default(true)
                    ->live()
                    ->columnSpanFull()
                    ->required(),

            ])->columns(3)
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        $emailSetting = EmailSettings::first(); // Gunakan EmailSetting, bukan Basic
        if ($emailSetting) {
            $emailSetting->update($data);
        } else {
            EmailSettings::create($data);
        }

        Notification::make()
            ->title('Settings Updated')
            ->body('Email setting has been successfully updated.')
            ->success()
            ->send();
    }
}
