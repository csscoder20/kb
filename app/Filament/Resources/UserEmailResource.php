<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Mail\MassEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Notifications\Notification;
use App\Filament\Resources\UserEmailResource\Pages;
use App\Models\EmailSettings;
use Illuminate\Support\Facades\Config;

class UserEmailResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'User Emails';
    protected static ?string $modelLabel = 'User Emails';
    protected static ?string $navigationGroup = 'Users Management';

    public function mount()
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 204);
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Regist Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Active Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Never'),
                IconColumn::make('email_verified_at')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(fn($query) => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->query(fn($query) => $query->whereNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('sendMassEmail')
                    ->label('Send Mass Email')
                    ->icon('heroicon-o-paper-airplane')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Email Subject')
                            ->required(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Email Content')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, Collection $records) {
                        // Ambil konfigurasi email dari database
                        $emailSettings = EmailSettings::getAllAsArray();

                        // Debug: Lihat apa yang ada di konfigurasi
                        logger()->info('Email settings:', $emailSettings);

                        // Periksa apakah email diaktifkan
                        if (
                            empty($emailSettings) || !isset($emailSettings['status']) ||
                            ($emailSettings['status'] !== true && $emailSettings['status'] !== '1' && $emailSettings['status'] !== 1)
                        ) {
                            logger()->warning('Email not configured or disabled');
                            Notification::make()
                                ->title('Email Not Configured')
                                ->body('Please configure and enable email settings first.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Konfigurasi mailer berdasarkan pengaturan dari database
                        if ($emailSettings['driver'] === 'smtp') {
                            Config::set('mail.default', 'smtp');
                            Config::set('mail.mailers.smtp.host', $emailSettings['host'] ?? '');
                            Config::set('mail.mailers.smtp.port', $emailSettings['port'] ?? '');
                            Config::set('mail.mailers.smtp.encryption', $emailSettings['encryption'] ?? 'tls');
                            Config::set('mail.mailers.smtp.username', $emailSettings['username'] ?? '');
                            Config::set('mail.mailers.smtp.password', $emailSettings['password'] ?? '');
                        } elseif ($emailSettings['driver'] === 'mailgun') {
                            Config::set('mail.default', 'mailgun');
                            Config::set('services.mailgun.domain', $emailSettings['domain'] ?? '');
                            Config::set('services.mailgun.secret', $emailSettings['secret_key'] ?? '');
                            Config::set('services.mailgun.endpoint', $emailSettings['region'] === 'EU' ? 'api.eu.mailgun.net' : 'api.mailgun.net');
                        }

                        // Set pengirim email
                        Config::set('mail.from.address', $emailSettings['email'] ?? 'noreply@example.com');
                        Config::set('mail.from.name', $emailSettings['name'] ?? 'System');

                        $count = 0;

                        foreach ($records as $user) {
                            if ($user->email) {
                                Mail::to($user->email)
                                    ->queue(new MassEmail($user, $data['subject'], $data['content']));
                                $count++;
                            }
                        }

                        Notification::make()
                            ->title('Mass Email Queued')
                            ->body("Email will be sent to {$count} recipients.")
                            ->success()
                            ->send();
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserEmails::route('/'),
        ];
    }
}
