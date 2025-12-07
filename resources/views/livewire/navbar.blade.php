<nav class="w-full border-b border-[#f1e6dc] bg-white sticky top-0 z-50">
    <div class="mx-auto flex w-full max-w-screen-2xl items-center justify-between gap-6 px-6 py-3 sm:px-8 lg:px-14">
        <div class="flex items-center gap-3">
            @if (request()->routeIs('search') || request()->routeIs('product.show'))
                <a href="{{ route('landing') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full text-[#3a2a22] transition bg-transparent hover:bg-[#c19a6b] hover:text-white">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 12.6667L3.33334 8L8 3.33333" stroke="#3E2723" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12.6667 8H3.33334" stroke="#3E2723" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
            <x-logo />
        </div>

        <div class="flex flex-1 justify-center">
            @livewire('search-bar')
        </div>

        <div class="flex items-center gap-6 text-[#3a2a22]">
            @livewire('cart-icon')
            @livewire('user-menu')
        </div>
    </div>
</nav>
