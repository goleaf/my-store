<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title>{{ $title ?? 'FreshCart - Auth' }}</title>
    <meta
        name="description"
        content="FreshCart - Ecommerce Laravel Application"
    >
    <link
        href="{{ asset('css/app.css') }}"
        rel="stylesheet"
    >

    <link
        rel="icon"
        href="{{ asset('favicon.svg') }}"
    >
    @livewireStyles
</head>

<body class="antialiased text-gray-900 bg-white">
    <header class="py-6 border-b border-gray-100">
        <div class="container mx-auto px-8">
            <a href="{{ route('home') }}">
                <x-brand.logo class="w-auto h-8 text-green-600" />
            </a>
        </div>
    </header>

    <main class="min-h-[calc(100vh-81px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
