<a href="{{ $cartUrl }}" class="relative inline-flex h-9 w-9 items-center justify-center rounded-full text-[#3a2a22] transition-colors bg-transparent hover:bg-[#c79c68]">
    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_11_282)">
            <path d="M15.3333 24.6667C15.7015 24.6667 16 24.3682 16 24C16 23.6318 15.7015 23.3333 15.3333 23.3333C14.9651 23.3333 14.6667 23.6318 14.6667 24C14.6667 24.3682 14.9651 24.6667 15.3333 24.6667Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M22.6667 24.6667C23.0349 24.6667 23.3333 24.3682 23.3333 24C23.3333 23.6318 23.0349 23.3333 22.6667 23.3333C22.2985 23.3333 22 23.6318 22 24C22 24.3682 22.2985 24.6667 22.6667 24.6667Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M11.3667 11.3667H12.7L14.4734 19.6467C14.5384 19.9499 14.7071 20.221 14.9505 20.4132C15.1939 20.6055 15.4966 20.7069 15.8067 20.7H22.3267C22.6301 20.6995 22.9244 20.5955 23.1607 20.4052C23.3971 20.2149 23.5615 19.9497 23.6267 19.6533L24.7267 14.7H13.4134" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
        </g>
        <defs>
            <clipPath id="clip0_11_282">
                <rect width="16" height="16" fill="white" transform="translate(10 10)"/>
            </clipPath>
        </defs>
    </svg>
    <span data-cart-count @class([
        'absolute -top-1 -right-1 min-w-[22px] h-5 px-1.5 rounded-full bg-[#7a4b24] text-white text-xs font-semibold flex items-center justify-center',
        'hidden' => ! $showBadge,
    ])>
        {{ $totalQuantity }}
    </span>
</a>
