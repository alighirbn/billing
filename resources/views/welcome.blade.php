<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BillFlow') }} - Subscription Management Made Simple</title>

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

            @keyframes slide-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-slide-up {
                animation: slide-up 0.8s ease-out forwards;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-blue-900 relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute w-96 h-96 -top-48 -left-48 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
                <div class="absolute w-96 h-96 -bottom-48 -right-48 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
                <div class="absolute w-96 h-96 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse animation-delay-4000"></div>
            </div>

            <!-- Grid Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cdefs%3E%3Cpattern id=\"grid\" width=\"60\" height=\"60\" patternUnits=\"userSpaceOnUse\"%3E%3Cpath d=\"M 60 0 L 0 0 0 60\" fill=\"none\" stroke=\"white\" stroke-width=\"0.5\" opacity=\"0.05\"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=\"100%25\" height=\"100%25\" fill=\"url(%23grid)\"/%3E%3C/svg%3E')] pointer-events-none"></div>

            <!-- Navigation -->
            <nav class="relative z-20 border-b border-gray-700/50 bg-gray-900/30 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="/" class="flex items-center group">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25 group-hover:shadow-purple-500/40 transition-all duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="ml-3 text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300">
                                    {{ config('app.name', 'BillFlow') }}
                                </span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="#features" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm font-semibold">
                                Features
                            </a>
                            <a href="#pricing" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm font-semibold">
                                Pricing
                            </a>
                            <a href="#about" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm font-semibold">
                                About
                            </a>
                        </div>

                        <!-- Auth Buttons -->
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-lg text-white font-semibold transition-all duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm font-semibold">
                                    Sign In
                                </a>
                                <a href="{{ route('register') }}" class="group relative">
                                    <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg blur opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                    <span class="relative inline-flex items-center px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg font-bold text-white text-sm">
                                        Get Started
                                        <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <section class="relative z-10 py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-purple-500/20 border border-purple-500/30 mb-8 animate-slide-up">
                            <svg class="w-4 h-4 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="text-sm font-semibold text-purple-300">Trusted by 10,000+ businesses</span>
                        </div>

<!-- Hero Title -->
<h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-200 to-blue-200 mb-6 leading-tight animate-slide-up">
    Simple, Secure<br>Cloud Backup
</h1>

<!-- Hero Description -->
<p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-12 animate-slide-up">
    Protect your files, photos, and business data with end-to-end encrypted cloud backup. Lightning-fast restores, zero configuration, and peace of mind — always.
</p>

<!-- Feature Highlight -->
<div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-lg border border-white/20 animate-slide-up max-w-3xl mx-auto">
    <h2 class="text-3xl font-extrabold text-white mb-4">2 TB Cloud Backup</h2>
    <p class="text-gray-300 leading-relaxed mb-6">
        Your secure space to store what matters. Enjoy encrypted backups, automatic sync, and fast recovery whenever you need it.
    </p>

    <ul class="grid sm:grid-cols-2 gap-4 text-gray-300 mb-8">
        <li class="flex items-center space-x-2">
            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
            <span>2 TB encrypted cloud storage</span>
        </li>
        <li class="flex items-center space-x-2">
            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
            <span>Automatic scheduled backups</span>
        </li>
        <li class="flex items-center space-x-2">
            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
            <span>Fast restore & file recovery</span>
        </li>
        <li class="flex items-center space-x-2">
            <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
            <span>Unlimited devices</span>
        </li>
    </ul>


</div>

<br>


                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up">
                            <a href="{{ route('register') }}" class="group relative w-full sm:w-auto">
                                <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 rounded-xl blur-xl opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                <span class="relative inline-flex items-center justify-center w-full px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-xl font-bold text-white text-lg group-hover:shadow-2xl group-hover:shadow-purple-500/50 transition-all duration-300">
                                    Start Free Trial
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </a>
                            <a href="#features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-xl text-white font-bold text-lg transition-all duration-300">
                                Learn More
                            </a>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="mt-16 flex items-center justify-center space-x-8 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                SSL Secured
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                PCI Compliant
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                99.9% Uptime
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="relative z-10 py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-16">
                        <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
                            Everything You Need
                        </h2>
                        <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                            Powerful features to manage your subscriptions and grow your business
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Instant Activation</h3>
                                <p class="text-gray-400">
                                    Get started immediately with automatic subscription activation and instant access to all features.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Secure Payments</h3>
                                <p class="text-gray-400">
                                    Bank-level encryption and PCI compliance ensure your payment data is always protected.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Analytics Dashboard</h3>
                                <p class="text-gray-400">
                                    Track your revenue, subscriptions, and growth with powerful real-time analytics.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Easy Cancellation</h3>
                                <p class="text-gray-400">
                                    No long-term commitments. Cancel anytime with just one click - we keep it simple.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">24/7 Support</h3>
                                <p class="text-gray-400">
                                    Our dedicated support team is always here to help you succeed with your subscriptions.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8 hover:border-gray-700 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mb-6">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-3">Automatic Renewals</h3>
                                <p class="text-gray-400">
                                    Never miss a payment with automatic renewal handling and smart retry logic.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="relative z-10 py-20 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur-2xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                        <div class="relative bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 p-12">
                            <div class="text-center">
                                <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
                                    Ready to Get Started?
                                </h2>
                                <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                                    Join thousands of businesses managing their subscriptions with {{ config('app.name', 'BillFlow') }}
                                </p>
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                    <a href="{{ route('register') }}" class="group relative w-full sm:w-auto">
                                        <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 rounded-xl blur-xl opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                        <span class="relative inline-flex items-center justify-center w-full px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-xl font-bold text-white text-lg">
                                            Start Your Free Trial
                                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </span>
                                    </a>
                                    <a href="{{ route('pricing') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-xl text-white font-bold text-lg transition-all duration-300">
                                        View Pricing
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="relative z-10 border-t border-gray-700/50 bg-gray-900/30 backdrop-blur-xl mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid md:grid-cols-4 gap-8">
                        <!-- Brand -->
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="ml-3 text-xl font-black text-white">
                                    {{ config('app.name', 'BillFlow') }}
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm">
                                Subscription management made simple for modern businesses.
                            </p>
                        </div>

                        <!-- Product -->
                        <div>
                            <h3 class="text-white font-bold mb-4">Product</h3>
                            <ul class="space-y-2">
                                <li><a href="#features" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                                <li><a href="{{ route('pricing') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">API</a></li>
                            </ul>
                        </div>

                        <!-- Company -->
                        <div>
                            <h3 class="text-white font-bold mb-4">Company</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">About</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                            </ul>
                        </div>

                        <!-- Legal -->
                        <div>
                            <h3 class="text-white font-bold mb-4">Legal</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Refund Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="mt-12 pt-8 border-t border-gray-700/50">
                        <div class="flex flex-col md:flex-row items-center justify-between">
                            <p class="text-gray-500 text-sm">
                                © {{ date('Y') }} {{ config('app.name', 'BillFlow') }}. All rights reserved.
                            </p>
                            <div class="flex items-center space-x-6 mt-4 md:mt-0">
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>