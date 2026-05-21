<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            /* Prevents the 'jump' by reserving the space exactly */
            img {
                content-visibility: auto;
            }
            
            /* This keeps the circle from shifting during load */
            .logo-wrapper {
                contain: strict;
                width: 176px;
                height: 176px;
            }
        </style>
        <link rel="preload" as="image" href="{{ asset('images/logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col md:flex-row">
            
            {{-- Left Side Panel: Stays Forest Green --}}
            <div class="flex flex-col flex-1 items-center justify-center p-12 text-center text-white" style="background-color: #228B22;">
                <div class="mb-6 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" 
                        alt="DairyBest Logo" 
                        class="w-48 h-48 object-contain drop-shadow-2xl"
                        decoding="sync"
                        loading="eager">
                </div>
                <h1 class="text-6xl font-bold mb-2">DairyBest</h1>
                <p class="text-2xl font-light opacity-90">Supplier Management System</p>
                <p class="text-lg mt-2 opacity-80 font-medium">Premium Milk Products from Davao</p>
            </div>

            <div class="flex-1 bg-white flex flex-col items-center justify-center p-6 relative">
    
                {{-- Only this White Box has the dynamic top border color --}}
                <div class="w-full {{ Request::is('login') || Request::is('forgot-password') ? 'max-w-md' : 'max-w-xl' }} bg-white px-10 py-8 rounded-3xl shadow-[0_20px_60px_-10px_rgba(0,0,0,0.2)] border border-gray-100 border-t-8" 
                style="border-top-color: {{ 
                    Auth::check() && Auth::user()->status === 'revoked' ? '#991b1b' : 
                    (Auth::check() && Auth::user()->status === 'declined' ? '#dc2626' : '#228B22') 
                }};">
                    
                    {{ $slot }}
                </div>

                <div class="absolute bottom-8 text-center text-gray-400 text-sm">
                    © 2026 DairyBest. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>