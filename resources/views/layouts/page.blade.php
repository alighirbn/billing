<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-blue-900">
        <!-- Navigation -->
        <nav class="border-b border-gray-700/50 bg-gray-900/30 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center group">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-xl font-black text-white">{{ config('app.name') }}</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white transition-colors text-sm font-semibold">Home</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-lg text-white font-semibold transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors text-sm font-semibold">Sign In</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white text-sm">Get Started</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main class="py-12">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-700/50 bg-gray-900/30 backdrop-blur-xl mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-500 text-sm">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>