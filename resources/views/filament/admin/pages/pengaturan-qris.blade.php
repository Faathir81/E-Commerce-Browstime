<x-filament-panels::page>

    <form wire:submit.prevent="simpan">

        {{ $this->form }}

        <div style="text-align: center;">
            <x-filament::button color="warning" wire:click="simpan">
                Simpan
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>
