@props([
    'variant' => 'primary',
    'type' => 'submit',
])

@php
    $base = 'w-full inline-flex items-center justify-center rounded-full px-5 py-3 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2';
    $classes = $variant === 'secondary'
        ? $base . ' bg-[#f6ede4] text-[#3b241a] hover:bg-[#ebddce] focus:ring-[#d7b08a]'
        : $base . ' bg-[#7a4b24] text-white hover:bg-[#643c1d] focus:ring-[#d7b08a]';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
