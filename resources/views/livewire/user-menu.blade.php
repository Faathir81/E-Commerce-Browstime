@guest
    <a href="{{ route('login') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-[#3a2a22] transition-colors bg-transparent hover:bg-[#c79c68]">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.6667 14V12.6667C12.6667 11.9594 12.3857 11.2811 11.8856 10.781C11.3855 10.281 10.7073 10 10 10H6.00001C5.29277 10 4.61449 10.281 4.11439 10.781C3.61429 11.2811 3.33334 11.9594 3.33334 12.6667V14" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8.00001 7.33333C9.47277 7.33333 10.6667 6.13943 10.6667 4.66667C10.6667 3.19391 9.47277 2 8.00001 2C6.52725 2 5.33334 3.19391 5.33334 4.66667C5.33334 6.13943 6.52725 7.33333 8.00001 7.33333Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
@else
    <div x-data="{ open: false }" class="relative">
        <button type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full text-[#3a2a22] transition-colors bg-transparent hover:bg-[#c79c68]"
                @click="open = !open"
                @click.outside="open = false"
                aria-haspopup="true"
                :aria-expanded="open">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6667 14V12.6667C12.6667 11.9594 12.3857 11.2811 11.8856 10.781C11.3855 10.281 10.7073 10 10 10H6.00001C5.29277 10 4.61449 10.281 4.11439 10.781C3.61429 11.2811 3.33334 11.9594 3.33334 12.6667V14" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.00001 7.33333C9.47277 7.33333 10.6667 6.13943 10.6667 4.66667C10.6667 3.19391 9.47277 2 8.00001 2C6.52725 2 5.33334 3.19391 5.33334 4.66667C5.33334 6.13943 6.52725 7.33333 8.00001 7.33333Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div x-cloak x-show="open" x-transition
             class="absolute right-0 mt-2 w-64 rounded-xl border border-[#f0e5d9] bg-white shadow-lg text-sm text-[#3a241a]">
            <div class="px-3 py-2 border-b border-[#f0e5d9] font-semibold truncate">
                {{ auth()->user()->email ?? '' }}
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-[#f7efe6] transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4 22C4 18.6863 7.58172 16 12 16C16.4183 16 20 18.6863 20 22" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-2 hover:bg-[#f7efe6] transition">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 21H5C4.44772 21 4 20.5523 4 20V4C4 3.44772 4.44772 3 5 3H9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12H9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
@endguest
