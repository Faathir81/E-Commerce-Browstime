<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-add-url" content="{{ route('cart.add') }}">
    <title>{{ config('app.name', 'Browstime') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cart.js', 'resources/js/product-card.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-[#FFF9F4] text-[#2b1a14] antialiased">

    {{-- NAVBAR (hidden on cart page) --}}
    @unless (request()->routeIs('cart.index'))
        <livewire:navbar />
    @endunless
    <x-toast />

    {{-- PAGE CONTENT --}}
    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @unless (request()->routeIs('cart.index') || request()->routeIs('checkout.*'))
        <x-footer />
    @endunless

    @livewireScripts
    @stack('scripts')
</body>
</html>
