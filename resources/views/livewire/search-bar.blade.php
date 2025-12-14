<form wire:submit.prevent="goToResults"
      wire:mouseenter="openDropdown"
      wire:mouseleave="closeDropdown"
      class="relative z-[70] w-full max-w-[calc(100vw-2.5rem)] sm:max-w-md lg:max-w-xl mx-auto">

    <label class="relative flex items-center">
        <span class="absolute left-4 text-[#8a5c3a] pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m.7-5.65a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </span>

        <input
            type="text"
            name="keyword"
            wire:model.live.debounce.300ms="search"
            wire:keydown.enter.prevent="goToResults"
            wire:focus="openDropdown"
            wire:keydown.escape="$set('search','')"
            placeholder="Search cookies & brownies..."
            class="w-full rounded-full border border-[#d3b495] bg-white pl-10 pr-10 py-2 text-sm text-[#4a2f22]
            focus:outline-none focus:ring-2 focus:ring-[#bb936c]"
        >

        @if ($showClear)
            <button type="button"
                    wire:click="clearSearch"
                    class="absolute right-2 inline-flex items-center justify-center w-8 h-8 rounded-full text-[#3a2a22] transition bg-transparent hover:bg-[#c19a6b] hover:text-white">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.9921 3.99756L3.99731 11.9924" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.99731 3.99756L11.9921 11.9924" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        @endif
    </label>

    @if ($dropdownOpen && $hasSearch)
        <div class="{{ $dropdownClasses }}">
            <div wire:loading.class="opacity-50">
                @if ($results->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        No matching products.
                    </div>
                @else
                    @foreach ($results as $item)
                        <x-search.result-item :item="$item" />
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</form>
