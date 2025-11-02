<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-2">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-gray-400 text-lg">
                    Here's what's happening with your account today.
                </p>
            </div>

            <!-- Success Message -->
            @if(request()->get('payment') === 'success')
                <div class="mb-8">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl blur opacity-30 animate-pulse"></div>
                        <div class="relative bg-gray-900/90 backdrop-blur-xl border border-green-500/30 rounded-2xl p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-green-300">Payment Successful! 🎉</h3>
                                    <p class="text-sm text-green-400 mt-1">
                                        Your subscription is now active. Welcome to the family!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Subscription Status Card -->
                <div class="lg:col-span-2">
                    <div class="relative group">
                        @php
                            $subscription = Auth::user()->subscription;
                            $isActive = $subscription && $subscription->status === 'active';
                            $isPending = $subscription && $subscription->status === 'pending';
                        @endphp

                        @if($isActive)
                            <!-- Active Subscription -->
                            <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
                        @elseif($isPending)
                            <!-- Pending Subscription -->
                            <div class="absolute -inset-1 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
                        @else
                            <!-- No Subscription -->
                            <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-500"></div>
                        @endif

                        <div class="relative bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50">
                            <!-- Decorative Header -->
                            @if($isActive)
                                <div class="relative h-2 bg-gradient-to-r from-green-600 to-emerald-600"></div>
                            @elseif($isPending)
                                <div class="relative h-2 bg-gradient-to-r from-yellow-600 to-orange-600"></div>
                            @else
                                <div class="relative h-2 bg-gradient-to-r from-purple-600 to-blue-600"></div>
                            @endif

                            <div class="p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-2xl font-black text-white">Subscription Status</h2>
                                    
                                    @if($isActive)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-500/20 border border-green-500/30">
                                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                            <span class="text-sm font-semibold text-green-400">ACTIVE</span>
                                        </span>
                                    @elseif($isPending)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500/20 border border-yellow-500/30">
                                            <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                                            <span class="text-sm font-semibold text-yellow-400">PENDING</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-gray-500/20 border border-gray-500/30">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                            <span class="text-sm font-semibold text-gray-400">NO PLAN</span>
                                        </span>
                                    @endif
                                </div>

                                @if($isActive)
                                    <!-- Active Subscription Details -->
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-gray-700/30">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-400">Current Plan</p>
                                                    <p class="text-lg font-bold text-white capitalize">{{ $subscription->plan }} Plan</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-400">
                                                    @if($subscription->plan === 'monthly')
                                                        $29
                                                    @else
                                                        $290
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    @if($subscription->plan === 'monthly')
                                                        per month
                                                    @else
                                                        per year
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        @if($subscription->current_period_end)
                                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-gray-700/30">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm text-gray-400">Next Billing Date</p>
                                                        <p class="text-lg font-bold text-white">{{ $subscription->current_period_end->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-400">
                                                        {{ $subscription->current_period_end->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="pt-4 flex gap-3">
                                            <a href="#" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-xl text-white font-semibold transition-all duration-300">
                                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                Manage Plan
                                            </a>
                                            <a href="#" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 font-semibold transition-all duration-300">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                @elseif($isPending)
                                    <!-- Pending Subscription -->
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-white mb-2">Processing Your Subscription</h3>
                                        <p class="text-gray-400 mb-6">We're setting up your {{ $subscription->plan }} plan. This usually takes just a few moments.</p>
                                        <a href="{{ route('pricing') }}" class="inline-flex items-center px-6 py-3 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-xl text-white font-semibold transition-all duration-300">
                                            View Plans
                                        </a>
                                    </div>
                                @else
                                    <!-- No Subscription -->
                                    <div class="text-center py-8">
                                        <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-white mb-2">Ready to Get Started?</h3>
                                        <p class="text-gray-400 mb-6">Choose a plan and unlock all premium features today.</p>
                                        <a href="{{ route('pricing') }}" class="group relative inline-flex">
                                            <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl blur-lg opacity-70 group-hover:opacity-100 transition-opacity"></span>
                                            <span class="relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl font-bold text-white">
                                                View Pricing Plans
                                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats & Account Info -->
                <div class="space-y-6">
                    <!-- Account Info Card -->
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                        <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Account Info
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Name</p>
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Email</p>
                                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Member Since</p>
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-700/50">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-sm font-semibold text-cyan-400 hover:text-cyan-300 transition-colors">
                                    Edit Profile
                                    <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                        <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Quick Actions
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('pricing') }}" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl border border-gray-700/30 transition-all duration-200 group/item">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-300 group-hover/item:text-white">View Plans</span>
                                        <svg class="w-4 h-4 text-gray-500 group-hover/item:text-white group-hover/item:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                                <a href="#" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl border border-gray-700/30 transition-all duration-200 group/item">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-300 group-hover/item:text-white">Billing History</span>
                                        <svg class="w-4 h-4 text-gray-500 group-hover/item:text-white group-hover/item:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                                <a href="#" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl border border-gray-700/30 transition-all duration-200 group/item">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-300 group-hover/item:text-white">Support</span>
                                        <svg class="w-4 h-4 text-gray-500 group-hover/item:text-white group-hover/item:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>