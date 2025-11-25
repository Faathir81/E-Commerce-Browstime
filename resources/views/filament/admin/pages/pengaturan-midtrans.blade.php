<x-filament-panels::page>
    <form wire:submit.prevent="simpan" class="space-y-6">

        <div class="space-y-4">
            {{ $this->form }}
        </div>

        <div class="pt-6">
            <x-filament::button type="submit">
                Simpan
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>