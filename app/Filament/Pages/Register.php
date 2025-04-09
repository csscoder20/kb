<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Register extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static string $view = 'filament.pages.register';
    protected static bool $shouldRegisterNavigation = false;
    public $name, $email, $password, $password_confirmation;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('Nama'),
            Forms\Components\TextInput::make('email')
                ->required()
                ->email(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->label('Password'),
            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->required()
                ->same('password')
                ->label('Konfirmasi Password'),
        ];
    }

    public function register()
    {
        $data = $this->form->getState();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Optional: assign default role
        $user->assignRole('user'); // Pastikan role 'user' sudah ada ya

        Auth::login($user);

        return redirect()->route('filament.admin.pages.dashboard');
    }

    protected function getFormModel(): string
    {
        return User::class;
    }

    public static function getMiddleware(): array
    {
        return ['guest'];
    }
}
