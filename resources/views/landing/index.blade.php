@extends('layouts.app')

@section('content')

    {{-- HERO SECTION FULL WIDTH --}}
    @include('landing.partials.hero')

    {{-- CONTENT --}}
    <x-category-section />

    @livewire('product-list')

    @include('landing.partials.best-seller', ['bestSellers' => $bestSellers])

    @include('layouts.partials.footer')

@endsection
