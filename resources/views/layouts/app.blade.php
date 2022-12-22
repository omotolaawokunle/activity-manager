<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SOFADRIC') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100" id="app">
        @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
        @elseif (session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
        @endif
        <div class="flex flex-wrap">
            @if(request()->user()->isAdmin())
                @include('layouts.sidebar')
                <div class="w-full min-h-screen pl-0 bg-white lg:pl-64" :class="sidebarOpen ? 'overlay' : ''"
                    id="main-content">
            @else
                <div class="w-full min-h-screen bg-white" id="main-content">
            @endif
            @include('layouts.navigation')

                <!-- Page Content -->
                <main class="mb-20">
                    <!-- Page Heading -->
                    @if(isset($header))
                        <header>
                            {{ $header }}
                        </header>
                    @endif
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>
