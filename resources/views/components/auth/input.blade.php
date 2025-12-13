@props([
    'id',
    'name',
    'type' => 'text',
    'label' => null,
    'placeholder' => '',
    'icon' => null,
    'value' => null,
    'showToggle' => false,
])

<div class="space-y-2 max-w-lg mx-auto">
    @if($label)
        <label for="{{ $id }}" class="text-sm font-semibold text-[#3b241a]">{{ $label }}</label>
    @endif

    <div
        @if($showToggle && $type === 'password')
            x-data="{ show: false }"
        @endif
        class="flex items-center gap-2 rounded-2xl border border-[#e9dccf] bg-white px-3 py-2 focus-within:ring-2 focus-within:ring-[#c79c68]"
    >
        @if($icon)
            <span class="text-[#b28757]">
                {!! $icon !!}
            </span>
        @endif
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            @if($showToggle && $type === 'password')
                :type="show ? 'text' : 'password'"
                x-bind="{}"
            @else
                type="{{ $type }}"
            @endif
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'w-full border-none focus:outline-none focus:ring-0 text-sm text-[#3b241a] placeholder-[#b9a797] bg-transparent']) }}
            @if($type === 'password') autocomplete="current-password" @endif
            @if($type === 'email') autocomplete="username" @endif
        />
        @if($showToggle && $type === 'password')
            <button type="button"
                    class="inline-flex items-center justify-center h-6 w-6 text-[#b28757] hover:text-[#7a4b24] transition"
                    @click="show = !show"
                    aria-label="Toggle password visibility">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.147-3.568m3.098-2.554A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.043 5.197M3 3l18 18" />
                </svg>
            </button>
        @endif
    </div>
    @error($name)
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
