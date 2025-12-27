<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-950 scheme-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <link rel="icon" href="{{ asset('images/logo_mascot.svg') }}" type="image/x-icon">
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sansantialiased">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white dark:bg-gray-950 scheme-light dark:scheme-dark">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="items-center sm:justify-center flex flex-col mb-4">
                <a href="/">
                    <img src="{{ asset('images/logo_main.svg') }}" class="w-42 h-42" />
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>

</body>

</html>
