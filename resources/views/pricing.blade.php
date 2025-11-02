<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl mb-6 shadow-2xl shadow-purple-500/30 animate-pulse">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-4">
                    Choose Your Plan
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Select the perfect plan for your needs. All plans include full access to our platform.
                </p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-8 max-w-3xl mx-auto">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-pink-600 rounded-2xl blur opacity-30"></div>
                        <div class="relative bg-gray-900/90 backdrop-blur-xl border border-red-500/30 rounded-2xl p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-red-300">
                                        {{ $errors->first() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pricing Cards -->
            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-16">
                <!-- Monthly Plan -->
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
                    
                    <div class="relative bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 transform hover:scale-105 transition-all duration-300">
                        <!-- Card Header -->
                        <div class="relative h-2 bg-gradient-to-r from-blue-600 to-cyan-600"></div>
                        
                        <div class="p-8">
                            <!-- Plan Badge -->
                            <div class="inline-flex items-center px-4 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 mb-6">
                                <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-sm font-semibold text-blue-400">FLEXIBLE</span>
                            </div>

                            <!-- Plan Name -->
                            <h3 class="text-3xl font-black text-white mb-2">Monthly Plan</h3>
                            <p class="text-gray-400 mb-6">Perfect for getting started</p>

                            <!-- Price -->
                            <div class="mb-8">
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">$29</span>
                                    <span class="text-2xl text-gray-400 ml-2">/month</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Billed monthly • Cancel anytime</p>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Full platform access</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Premium features</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">24/7 support</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-blue-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Cancel anytime</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <form method="POST" action="{{ route('checkout') }}">
                                @csrf
                                <button type="submit" name="plan" value="monthly" class="w-full group relative">
                                    <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl blur-lg opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                    <span class="relative flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl group-hover:shadow-2xl group-hover:shadow-blue-500/50 transition-all duration-300 font-bold text-white text-lg">
                                        Get Started
                                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Annual Plan (Popular) -->
                <div class="relative group">
                    <!-- Popular Badge -->
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2 z-20">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg shadow-green-500/50 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            MOST POPULAR
                        </div>
                    </div>

                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600 rounded-3xl blur-xl opacity-60 group-hover:opacity-90 transition-opacity duration-500 animate-pulse"></div>
                    
                    <div class="relative bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-purple-500/50 transform hover:scale-105 transition-all duration-300">
                        <!-- Card Header -->
                        <div class="relative h-2 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600"></div>
                        
                        <div class="p-8">
                            <!-- Plan Badge -->
                            <div class="inline-flex items-center px-4 py-1 rounded-full bg-purple-500/20 border border-purple-500/30 mb-6">
                                <svg class="w-4 h-4 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="text-sm font-semibold text-purple-400">BEST VALUE</span>
                            </div>

                            <!-- Plan Name -->
                            <h3 class="text-3xl font-black text-white mb-2">Annual Plan</h3>
                            <p class="text-gray-400 mb-6">Save 2 months with annual billing</p>

                            <!-- Price -->
                            <div class="mb-8">
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-400 to-orange-400">$290</span>
                                    <span class="text-2xl text-gray-400 ml-2">/year</span>
                                </div>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-gray-500 line-through mr-2">$348/year</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-500/20 border border-green-500/30 text-xs font-bold text-green-400">
                                        SAVE $58
                                    </span>
                                </div>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Everything in Monthly</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Priority support</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300">Early access to features</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-6 h-6 bg-purple-500/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-gray-300 font-semibold">Save $58 per year</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <form method="POST" action="{{ route('checkout') }}">
                                @csrf
                                <button type="submit" name="plan" value="annual" class="w-full group relative">
                                    <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-orange-600 rounded-xl blur-lg opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                    <span class="relative flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-600 rounded-xl group-hover:shadow-2xl group-hover:shadow-purple-500/50 transition-all duration-300 font-bold text-white text-lg">
                                        Get Started
                                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ or Trust Section -->
            <div class="max-w-4xl mx-auto">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-20"></div>
                    <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-white mb-6 text-center">What's Included</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h4 class="text-white font-semibold mb-2">30-Day Guarantee</h4>
                                <p class="text-gray-400 text-sm">Not satisfied? Get a full refund within 30 days</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h4 class="text-white font-semibold mb-2">Secure Payments</h4>
                                <p class="text-gray-400 text-sm">Bank-level encryption for all transactions</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-white font-semibold mb-2">Cancel Anytime</h4>
                                <p class="text-gray-400 text-sm">No long-term commitments or contracts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>