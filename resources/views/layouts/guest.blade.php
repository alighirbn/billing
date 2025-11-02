<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BillFlow') }} - Subscription Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes gradient-x {
                0%, 100% { transform: translateX(-100%); }
                50% { transform: translateX(100%); }
            }
            
            .animate-gradient-x {
                animation: gradient-x 3s ease infinite;
                background-size: 200% 200%;
            }
            
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            
            .animation-delay-4000 {
                animation-delay: 4s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-blue-900 relative overflow-hidden flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Animated Background -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute w-96 h-96 -top-48 -left-48 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
                <div class="absolute w-96 h-96 -bottom-48 -right-48 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
                <div class="absolute w-96 h-96 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse animation-delay-4000"></div>
            </div>

            <!-- Grid Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cdefs%3E%3Cpattern id=\"grid\" width=\"60\" height=\"60\" patternUnits=\"userSpaceOnUse\"%3E%3Cpath d=\"M 60 0 L 0 0 0 60\" fill=\"none\" stroke=\"white\" stroke-width=\"0.5\" opacity=\"0.05\"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=\"100%25\" height=\"100%25\" fill=\"url(%23grid)\"/%3E%3C/svg%3E')] pointer-events-none"></div>

            <!-- Content Container -->
            <div class="relative z-10 w-full px-4">
                <!-- Logo & Branding -->
                <div class="mb-8 text-center">
                    <a href="/" class="inline-flex flex-col items-center group">
                        <!-- Custom Logo -->
                        <div class="relative mb-4">
                            <div class="absolute -inset-2 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity animate-float"></div>
                            <div class="relative w-24 h-24 bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-purple-500/30 group-hover:shadow-purple-500/50 transition-all duration-300 group-hover:scale-110">
                                <!-- Billing Icon -->
                                <svg class="w-14 h-14 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- App Name -->
                        <div class="space-y-1">
                            <span class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-200 to-blue-200">
                                {{ config('app.name', 'BillFlow') }}
                            </span>
                            <p class="text-sm text-gray-400 font-medium tracking-wide">
                                Subscription Management Platform
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Card Container -->
                <div class="w-full sm:max-w-md mx-auto">
                    <div class="relative group">
                        <!-- Glow Effect -->
                        <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
                        
                        <!-- Card -->
                        <div class="relative bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50">
                            <!-- Decorative Header with Icons -->
                            <div class="relative h-2 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600">
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 animate-gradient-x"></div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-8">
                                {{ $slot }}
                            </div>

                            <!-- Card Footer -->
                            <div class="px-8 py-4 bg-gray-800/30 border-t border-gray-700/30">
                                <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                                    <!-- Trust Badges -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span>SSL Secured</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span>Bank-Level Encryption</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Links & Info -->
                <div class="mt-8 space-y-4">
                    <!-- Quick Links -->
                    <div class="flex items-center justify-center space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            Help Center
                        </a>
                        <span class="text-gray-700">•</span>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            Privacy Policy
                        </a>
                        <span class="text-gray-700">•</span>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            Terms of Service
                        </a>
                    </div>

                    <!-- Copyright -->
                    <div class="text-center">
                        <p class="text-gray-500 text-sm">
                            © {{ date('Y') }} {{ config('app.name', 'BillFlow') }}. All rights reserved.
                        </p>
                        <p class="text-gray-600 text-xs mt-1">
                            Powered by Paddle • Trusted by thousands of businesses
                        </p>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-8 max-w-2xl mx-auto">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Indicator 1 -->
                        <div class="relative group/indicator">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl blur opacity-0 group-hover/indicator:opacity-20 transition-opacity"></div>
                            <div class="relative bg-gray-900/30 backdrop-blur border border-gray-800/50 rounded-xl p-4 text-center">
                                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-400">99.9% Uptime</p>
                            </div>
                        </div>

                        <!-- Indicator 2 -->
                        <div class="relative group/indicator">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl blur opacity-0 group-hover/indicator:opacity-20 transition-opacity"></div>
                            <div class="relative bg-gray-900/30 backdrop-blur border border-gray-800/50 rounded-xl p-4 text-center">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-400">PCI Compliant</p>
                            </div>
                        </div>

                        <!-- Indicator 3 -->
                        <div class="relative group/indicator">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl blur opacity-0 group-hover/indicator:opacity-20 transition-opacity"></div>
                            <div class="relative bg-gray-900/30 backdrop-blur border border-gray-800/50 rounded-xl p-4 text-center">
                                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-400">24/7 Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>