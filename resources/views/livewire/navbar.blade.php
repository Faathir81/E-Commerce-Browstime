<nav class="w-full border-b border-[#f1e6dc] bg-white">
    <div class="mx-auto flex w-full max-w-screen-2xl items-center justify-between gap-6 px-6 py-3 sm:px-8 lg:px-14">
        <div class="flex items-center gap-3">
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
