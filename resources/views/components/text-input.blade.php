@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'w-full rounded-2xl border border-[#e9dccf] bg-white px-4 py-2.5 text-[#3b241a] placeholder:text-[#9c7a5e] focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68] shadow-sm transition-colors',
    ]) }}
>
