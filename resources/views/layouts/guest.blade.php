<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CommerceCore') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-surface-900 dark:text-surface-100 antialiased bg-surface-50 dark:bg-surface-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] rounded-full bg-primary-500/10 blur-[120px]"></div>
                <div class="absolute -bottom-[20%] -left-[10%] w-[60%] h-[60%] rounded-full bg-blue-500/10 blur-[100px]"></div>
            </div>

            <div class="z-10 w-full sm:max-w-md">
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-2xl font-display font-bold text-surface-900 dark:text-white">CommerceCore</span>
                    </a>
                </div>

                <div class="w-full px-8 py-10 bg-white/80 dark:bg-surface-800/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-3xl border border-surface-200/50 dark:border-surface-700/50">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
