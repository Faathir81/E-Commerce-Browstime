<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Browstime') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFF9F4] text-[#2b1a14] antialiased">

    {{-- NAVBAR --}}
    <livewire:navbar />

    {{-- PAGE CONTENT --}}
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

</body>
</html>
