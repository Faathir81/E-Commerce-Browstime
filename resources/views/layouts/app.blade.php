<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-add-url" content="{{ route('cart.add') }}">
    <title>{{ config('app.name', 'Browstime') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cart.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-[#FFF9F4] text-[#2b1a14] antialiased">

    {{-- NAVBAR --}}
    <livewire:navbar />

    {{-- PAGE CONTENT --}}
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
