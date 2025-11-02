<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-yellow-600 to-orange-600 rounded-2xl mb-4 shadow-lg shadow-yellow-500/30 animate-pulse">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300 mb-2">
            Verify Your Email
        </h2>
        <p class="text-gray-400 text-sm max-w-md mx-auto">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>
    </div>

    <!-- Success Message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 relative">
            <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl blur opacity-30 animate-pulse"></div>
            <div class="relative bg-gray-900/90 backdrop-blur-xl border border-green-500/30 rounded-xl p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-300">
                            A new verification link has been sent to your email address.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Info Card -->
    <div class="mb-6 relative">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl blur opacity-20"></div>
        <div class="relative bg-gray-900/50 backdrop-blur border border-gray-800 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-white mb-1">Check your inbox</h3>
                    <p class="text-sm text-gray-400">
                        If you didn't receive the email, we can send you another one.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <!-- Resend Verification Email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full group relative">
                <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 rounded-xl blur-lg opacity-70 group-hover:opacity-100 transition-opacity"></span>
                <span class="relative flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-xl font-bold text-white text-lg group-hover:shadow-2xl group-hover:shadow-purple-500/50 transition-all duration-300">
                    <svg class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Resend Verification Email
                </span>
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3 bg-white/5 hover:bg-white/10 border border-gray-700/50 rounded-xl text-white font-semibold transition-all duration-300 group">
                <svg class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out
            </button>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-500">
            Having trouble? 
            <a href="#" class="font-semibold text-purple-400 hover:text-purple-300 transition-colors">
                Contact Support
            </a>
        </p>
    </div>
</x-guest-layout>