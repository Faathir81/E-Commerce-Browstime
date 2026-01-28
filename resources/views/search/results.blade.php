@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-14 py-8">

    <div class="sm:hidden mb-4">
        @livewire('search-bar', [], key('search-bar-mobile'))
    </div>

    <div class="flex items-start gap-2 text-[#3b241a] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-4.35-4.35m.7-5.65a6 6 0 11-12 0 6 6 0 0112 0z" />
        </svg>
        <div>
            @if ($keyword !== '')
                <p class="text-base">
                    Hasil pencarian untuk
                </p>
                <p class="text-2xl font-semibold leading-tight">
                    {{ $keyword }}
                </p>
            @else
                <p class="text-base">
                    Semua Produk
                </p>
                <p class="text-2xl font-semibold leading-tight">
                    Jelajahi Semua Produk
                </p>
            @endif
            <p class="text-base text-[#6f4c3b] mt-1">
                {{ $results->total() }}
                {{ $keyword === '' ? 'pilihan tersedia' : 'produk ditemukan' }}
            </p>
        </div>
    </div>

    @if ($results->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
            Tidak ada produk ditemukan. Coba kata kunci lain.
        </div>
    @else
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @foreach ($results as $product)
                <x-product.card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $results->links() }}
        </div>
    @endif

</div>
@endsection
