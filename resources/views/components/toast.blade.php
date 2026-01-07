@php
    $toast = session('toast');
@endphp

@if ($toast)
    <div class="fixed top-6 left-1/2 z-50 w-[92%] -translate-x-1/2 sm:w-auto">
        <div class="toast-slide flex items-center gap-3 rounded-2xl border border-[#f1e8df] bg-white px-4 py-3 text-sm text-[#3b241a] shadow-lg">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#7a4b24] text-white">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="font-medium">{{ $toast['message'] ?? '' }}</span>
        </div>
    </div>
@endif
