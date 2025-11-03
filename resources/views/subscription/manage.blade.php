<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-2">
                    Manage Subscription
                </h1>
                <p class="text-gray-400 text-lg">
                    Update your plan, payment method, or cancel your subscription.
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-500/20 border border-green-500/30 rounded-xl p-4">
                    <p class="text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                    <p class="text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-500/20 border border-blue-500/30 rounded-xl p-4">
                    <p class="text-blue-400">{{ session('info') }}</p>
                </div>
            @endif

            <!-- Current Subscription -->
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 mb-8">
                <div class="h-2 bg-gradient-to-r from-green-600 to-emerald-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-black text-white mb-6">Current Plan</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white/5 rounded-xl p-4 border border-gray-700/30">
                            <p class="text-sm text-gray-400 mb-1">Plan</p>
                            <p class="text-2xl font-bold text-white capitalize">{{ $subscription->plan }}</p>
                        </div>
                        
                        <div class="bg-white/5 rounded-xl p-4 border border-gray-700/30">
                            <p class="text-sm text-gray-400 mb-1">Status</p>
                            <p class="text-2xl font-bold capitalize
                                @if($subscription->status === 'active') text-green-400
                                @elseif($subscription->status === 'canceled') text-red-400
                                @else text-yellow-400
                                @endif">
                                {{ $subscription->status }}
                            </p>
                        </div>
                        
                        @if($subscription->current_period_end)
                        <div class="bg-white/5 rounded-xl p-4 border border-gray-700/30">
                            <p class="text-sm text-gray-400 mb-1">Next Billing Date</p>
                            <p class="text-lg font-bold text-white">
                                {{ $subscription->current_period_end->format('M d, Y') }}
                            </p>
                        </div>
                        @endif
                        
                        <div class="bg-white/5 rounded-xl p-4 border border-gray-700/30">
                            <p class="text-sm text-gray-400 mb-1">Price</p>
                            <p class="text-lg font-bold text-white">
                                @if($subscription->plan === 'monthly')
                                    $299/month
                                @else
                                    $2,990/year
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Plan -->
            @if($subscription->status === 'active')
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 mb-8">
                <div class="h-2 bg-gradient-to-r from-purple-600 to-blue-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-black text-white mb-4">Change Plan</h2>
                    <p class="text-gray-400 mb-6">Switch between monthly and annual billing.</p>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Monthly Plan -->
                        <div class="relative bg-white/5 rounded-xl p-6 border-2
                            @if($subscription->plan === 'monthly') border-green-500 @else border-gray-700/30 @endif">
                            @if($subscription->plan === 'monthly')
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                    <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Current Plan
                                    </span>
                                </div>
                            @endif
                            
                            <h3 class="text-xl font-bold text-white mb-2">Monthly</h3>
                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400 mb-4">
                                $299<span class="text-sm text-gray-400">/month</span>
                            </p>
                            <p class="text-gray-400 mb-6">Billed monthly</p>
                            
                            @if($subscription->plan !== 'monthly')
                                <form action="{{ route('subscription.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="monthly">
                                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                                        Switch to Monthly
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <!-- Annual Plan -->
                        <div class="relative bg-white/5 rounded-xl p-6 border-2
                            @if($subscription->plan === 'annual') border-green-500 @else border-gray-700/30 @endif">
                            @if($subscription->plan === 'annual')
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                    <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Current Plan
                                    </span>
                                </div>
                            @endif
                            
                            <h3 class="text-xl font-bold text-white mb-2">Annual</h3>
                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400 mb-4">
                                $2,990<span class="text-sm text-gray-400">/year</span>
                            </p>
                            <p class="text-green-400 mb-6">Save $598 per year! 🎉</p>
                            
                            @if($subscription->plan !== 'annual')
                                <form action="{{ route('subscription.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="annual">
                                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                                        Switch to Annual
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment & Billing -->
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-700/50 mb-8">
                <div class="h-2 bg-gradient-to-r from-cyan-600 to-blue-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-black text-white mb-6">Payment & Billing</h2>
                    
                    <div class="space-y-4">
                        <a href="{{ route('subscription.update-payment') }}" class="block p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-gray-700/30 transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Update Payment Method</h3>
                                    <p class="text-sm text-gray-400">Change your credit card or payment details</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                        
                        <a href="{{ route('subscription.billing-history') }}" class="block p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-gray-700/30 transition-all duration-200 group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Billing History</h3>
                                    <p class="text-sm text-gray-400">View past invoices and payments</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cancel Subscription -->
            @if($subscription->status === 'active')
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-red-700/50">
                <div class="h-2 bg-gradient-to-r from-red-600 to-pink-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-black text-white mb-4">Cancel Subscription</h2>
                    <p class="text-gray-400 mb-6">
                        Your subscription will remain active until the end of your current billing period 
                        ({{ $subscription->current_period_end ? $subscription->current_period_end->format('M d, Y') : 'billing period end' }}).
                    </p>
                    
                    <form action="{{ route('subscription.cancel') }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel your subscription? You will still have access until {{ $subscription->current_period_end ? $subscription->current_period_end->format('M d, Y') : 'the end of your billing period' }}.');">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 font-semibold transition-all duration-300">
                            Cancel Subscription
                        </button>
                    </form>
                </div>
            </div>
            @elseif($subscription->status === 'canceled')
            <div class="bg-gray-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-yellow-700/50">
                <div class="h-2 bg-gradient-to-r from-yellow-600 to-orange-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-black text-white mb-4">Reactivate Subscription</h2>
                    <p class="text-gray-400 mb-6">
                        Your subscription is currently canceled. You can reactivate it to continue using our service.
                    </p>
                    
                    <form action="{{ route('subscription.reactivate') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-green-500/10 hover:bg-green-500/20 border border-green-500/30 rounded-xl text-green-400 font-semibold transition-all duration-300">
                            Reactivate Subscription
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>