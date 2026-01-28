@props(['step', 'stepView', 'config', 'stepData' => []])

@php
    extract($stepData, EXTR_SKIP);
@endphp

<form wire:submit.prevent="{{ $config['submit_action'] }}" class="space-y-6">
    @include($stepView, $stepData)

    <div class="{{ $config['show_back'] ? 'flex justify-between' : 'flex justify-end' }}">
        @if($config['show_back'])
            <button type="button"
                    class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition"
                    wire:click="{{ $config['back_action'] }}">
                {{ $config['back_label'] ?? 'Kembali' }}
            </button>
        @endif
        <button type="submit"
                class="inline-flex items-center justify-center {{ $config['submit_action'] === 'placeOrder' ? 'gap-2' : '' }} rounded-full bg-[#7a4b24] text-white px-6 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                wire:loading.attr="disabled">
            @if($config['submit_action'] === 'placeOrder')
                <span>{{ $config['submit_label'] }}</span>
            @else
                {{ $config['submit_label'] }}
            @endif
        </button>
    </div>
</form>
