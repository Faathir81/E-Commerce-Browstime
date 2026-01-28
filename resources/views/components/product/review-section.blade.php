@props(['summary'])

<section class="bg-white border border-[#f1e8df] rounded-3xl p-6 lg:p-8 shadow-sm">
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-6">
            <div class="min-w-[200px]">
                <p class="text-sm text-[#6f4c3b]">Ulasan Pelanggan</p>
                <div class="mt-2 flex items-end gap-3">
                    <span class="text-4xl font-semibold">{{ number_format($summary->average_rating, 1) }}</span>
                    <x-product.star-rating :rating="$summary->average_rating" size="18" class="mb-1" />
                </div>
                <p class="mt-2 text-xs text-[#6f4c3b]">{{ $summary->total_reviews }} ulasan</p>
            </div>

            @if($summary->total_reviews > 0)
                <div class="flex-1 min-w-[240px] space-y-2">
                    @foreach($summary->rating_breakdown as $row)
                        <div class="flex items-center gap-3 text-xs text-[#6f4c3b]">
                            <span class="w-6 text-right">{{ $row['rating'] }}★</span>
                            <div class="flex-1 h-2 rounded-full bg-[#f3e8dd] overflow-hidden">
                                <div class="h-full bg-[#b4662b]" style="width: {{ $row['percent'] }}%"></div>
                            </div>
                            <span class="w-10 text-right">{{ $row['percent'] }}%</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="border-t border-[#f1e8df] pt-6 space-y-6">
            @forelse($summary->reviews as $review)
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f4e9dc] text-sm font-semibold text-[#7a4b24]">
                        {{ $review['initials'] }}
                    </div>
                    <div class="flex-1 space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold">{{ $review['name'] }}</p>
                            <x-product.star-rating :rating="$review['rating']" size="14" />
                            @if($review['date'])
                                <span class="text-xs text-[#6f4c3b]">{{ $review['date'] }}</span>
                            @endif
                        </div>
                        @if(! empty($review['comment']))
                            <p class="text-sm text-[#5a4135] leading-relaxed">
                                {{ $review['comment'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#6f4c3b]">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </div>
</section>
