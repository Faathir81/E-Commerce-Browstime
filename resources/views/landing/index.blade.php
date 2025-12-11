@extends('layouts.app')

@section('content')

    {{-- HERO SECTION --}}
    @include('landing.partials.hero')

    {{-- CATEGORY SECTION --}}
    <x-category-section />

    {{-- SHOP DISPLAY SECTION --}}
    @include('landing.partials.shop-display', ['shopDisplay' => $shopDisplay])

@endsection
