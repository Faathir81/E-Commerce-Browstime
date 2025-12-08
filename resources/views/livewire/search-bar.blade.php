<form wire:submit.prevent="goToResults"
      wire:mouseenter="openDropdown"
      wire:mouseleave="closeDropdown"
      class="relative w-full max-w-xl mx-auto">

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

        @if (strlen(trim($search)) > 0)
            <button type="button"
                    wire:click="clearSearch"
                    class="absolute right-2 inline-flex items-center justify-center w-8 h-8 rounded-full text-[#3a2a22] transition bg-transparent hover:bg-[#c19a6b] hover:text-white">
                ✕
            </button>
        @endif
    </label>

    @if (strlen(trim($search)) >= 1 && $dropdownOpen)
        <div
            class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-lg border border-[#e0c8b0] max-h-80 overflow-y-auto z-[60] p-2"
        >
            <div wire:loading.class="opacity-50">
                @if ($this->results->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        No matching products.
                    </div>
                @else
                    @foreach ($this->results as $item)
                        <a wire:key="result-{{ $item->id }}"
                           href="{{ route('product.show', $item->slug) }}"
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#f9f1ea] transition">

                            <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : 'https://via.placeholder.com/60' }}"
                                 class="w-12 h-12 rounded-lg object-cover"
                                 alt="{{ $item->nama }}">

                            <div class="flex-1">
                                <p class="font-medium text-[#3b241a] truncate">
                                    {{ $item->nama }}
                                </p>
                                <p class="text-sm text-[#6f4c3b]">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                            </div>

                            <span class="text-xs bg-[#d7b28a] text-white px-3 py-1 rounded-full">
                                {{ optional($item->kategori)->nama ?? 'Produk' }}
                            </span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</form>
