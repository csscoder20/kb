<x-filament::page>
    <form wire:submit.prevent="authenticate" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full">
            Login
        </x-filament::button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('filament.pages.register') }}" class="text-primary-600 hover:underline">
            Belum punya akun? Daftar di sini
        </a>
    </div>
</x-filament::page>