@php
    // Hide the search bar on mobile for landing, search, and product detail to keep the header compact
    $hideSearchOnMobile = request()->routeIs('landing') || request()->routeIs('search') || request()->routeIs('product.show');
@endphp
<nav class="w-full border-b border-[#f1e6dc] bg-white sticky top-0 z-50">
    <div class="mx-auto flex w-full max-w-screen-2xl items-center flex-wrap justify-between gap-3 sm:gap-6 px-4 sm:px-6 lg:px-14 py-3 min-w-0">
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            @if (request()->routeIs('search') || request()->routeIs('product.show'))
                <a href="{{ route('landing') }}"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-full text-[#3a2a22] transition bg-transparent hover:bg-[#c19a6b] hover:text-white">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 12.6667L3.33334 8L8 3.33333" stroke="#3E2723" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12.6667 8H3.33334" stroke="#3E2723" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
            <x-logo />
        </div>

        <div class="{{ $hideSearchOnMobile ? 'hidden sm:flex' : 'flex' }} flex-1 justify-center min-w-0">
            @livewire('search-bar')
        </div>

        <div class="flex items-center gap-4 sm:gap-6 text-[#3a2a22] flex-shrink-0">
            @livewire('cart-icon')
            @livewire('user-menu')
        </div>
    </div>
</nav>
