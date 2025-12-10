@extends('layouts.app')

@section('content')

    {{-- HERO SECTION --}}
    @include('landing.partials.hero')

    {{-- CATEGORY SECTION --}}
    <x-category-section />

    {{-- BEST SELLER SECTION --}}
    @include('landing.partials.shop-display', ['bestSellers' => $bestSellers])

@endsection
