<?php

namespace App\Filament\Pages;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\EmailSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Mail\TestEmail;

class Emailsetting extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static string $view = 'filament.pages.emailsetting';
    protected static ?string $navigationLabel = 'Email';
    protected static ?string $modelLabel = 'Email';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Email';

    public ?array $data = [];

    // Jika paksa redirect, si Mas akan meneng bae
    public function mount()
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 204); // 204 = No Content

        $this->form->fill(EmailSettings::getAllAsArray());
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

        // Pastikan status diatur dengan benar
        if (!isset($data['status'])) {
            $data['status'] = false;
        }

        EmailSettings::setBulk($data);

        Notification::make()
            ->title('Settings Updated')
            ->body('Email settings have been successfully updated.')
            ->success()
            ->send();
    }

    public function testEmailConfig()
    {
        try {
            // Ambil konfigurasi email dari database
            $emailSettings = EmailSettings::getAllAsArray();

            // Debug: Log konfigurasi
            logger()->info('Testing email with config:', array_merge(
                $emailSettings,
                ['password' => '******'] // Sembunyikan password di log
            ));

            // Konfigurasi mailer berdasarkan pengaturan
            if ($emailSettings['driver'] === 'smtp') {
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $emailSettings['host'] ?? '');
                Config::set('mail.mailers.smtp.port', $emailSettings['port'] ?? '');
                Config::set('mail.mailers.smtp.encryption', $emailSettings['encryption'] ?? 'tls');
                Config::set('mail.mailers.smtp.username', $emailSettings['username'] ?? '');
                Config::set('mail.mailers.smtp.password', $emailSettings['password'] ?? '');
            }

            // Set pengirim email
            Config::set('mail.from.address', $emailSettings['email'] ?? 'noreply@example.com');
            Config::set('mail.from.name', $emailSettings['name'] ?? 'System');

            // Kirim email test ke user yang sedang login
            $user = Auth::user();
            Mail::to($user->email)->send(new TestEmail($user));

            Notification::make()
                ->title('Test Email Sent')
                ->body('A test email has been sent to your email address.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            logger()->error('Test email failed: ' . $e->getMessage());
            Notification::make()
                ->title('Test Email Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
