@extends('layouts.app')

@section('content')

    {{-- HERO SECTION --}}
    @include('landing.partials.hero')

    {{-- CATEGORY SECTION --}}
    <x-category-section />

    {{-- BEST SELLER SECTION --}}
    @include('landing.partials.best-seller', ['bestSellers' => $bestSellers])

    {{-- FOOTER --}}
    @include('layouts.partials.footer')

@endsection
