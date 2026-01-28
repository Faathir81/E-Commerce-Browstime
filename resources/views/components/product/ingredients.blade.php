@props(['items' => []])

@if(!empty($items))
    <div class="mt-8 rounded-2xl border border-[#f1e8df] bg-white p-5">
        <h3 class="font-semibold text-base mb-1">Bahan yang Digunakan</h3>
        <p class="text-xs text-[#6f4c3b] mb-4">Bahan premium di setiap batch</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach ($items as $item)
                <div class="flex flex-col gap-1 rounded-xl bg-[#f9f2eb] px-4 py-3 border border-[#f1e8df]">
                    <div class="flex items-start gap-2 text-sm font-semibold text-[#3b241a]">
                        <span class="mt-1 h-2 w-2 rounded-full bg-[#7a4b24]"></span>
                        <p class="truncate">{{ $item['name'] }}</p>
                    </div>
                    <p class="text-sm text-[#6f4c3b] ml-4">
                        {{ $item['amount'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
@endif
