<x-filament::layouts.base>
    <div class="w-full max-w-md mx-auto mt-10 space-y-4">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <x-filament::input label="Name" name="name" required />
            <x-filament::input label="Email" name="email" type="email" required />
            <x-filament::input label="Password" name="password" type="password" required />
            <x-filament::input label="Confirm Password" name="password_confirmation" type="password" required />
            <x-filament::button type="submit" class="w-full">
                Register
            </x-filament::button>
        </form>
    </div>
</x-filament::layouts.base>