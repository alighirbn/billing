<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                    Complete Your Purchase
                </h1>
                <p class="mt-3 text-xl text-gray-600">
                    You're one step away from getting started!
                </p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Secure Checkout</h2>
                    <p class="text-blue-100">Opening your checkout session...</p>
                </div>

                <!-- Card Body -->
                <div class="px-6 py-8">
                    <div id="checkout-container" class="text-center">
                        <!-- Loading Animation -->
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="relative">
                                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-6 text-lg text-gray-700 font-medium">Preparing your secure checkout...</p>
                            <p class="mt-2 text-sm text-gray-500">This will only take a moment</p>
                        </div>
                    </div>

                    <!-- Manual Open Button (Hidden by default) -->
                    <div class="text-center mt-6">
                        <button 
                            id="open-checkout-manual"
                            class="hidden inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                        >
                            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Click here to open checkout
                        </button>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-gray-900 font-medium inline-flex items-center transition-colors duration-200">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to pricing
                        </a>
                        <div class="flex items-center text-gray-500">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Secured by Paddle
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section (shown while checkout loads) -->
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100 mb-3">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">Secure Payment</h3>
                    <p class="mt-1 text-xs text-gray-500">SSL encrypted</p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 mb-3">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">Instant Access</h3>
                    <p class="mt-1 text-xs text-gray-500">Start immediately</p>
                </div>

                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-purple-100 mb-3">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">Cancel Anytime</h3>
                    <p class="mt-1 text-xs text-gray-500">No commitments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Paddle.js SDK -->
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    
    <script>
        // Initialize Paddle
        Paddle.Environment.set('{{ env("PADDLE_ENV") === "sandbox" ? "sandbox" : "production" }}');
        
        Paddle.Initialize({
            token: '{{ env("PADDLE_CLIENT_TOKEN") }}',
            eventCallback: function(data) {
                console.log('Paddle event:', data);
                
                if (data.name === 'checkout.loaded') {
                    console.log('Checkout loaded successfully');
                }
                
                if (data.name === 'checkout.completed') {
                    console.log('Payment completed successfully!');
                    
                    // Show success message before redirect
                    document.getElementById('checkout-container').innerHTML = 
                        '<div class="py-12 text-center">' +
                        '<div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">' +
                        '<svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' +
                        '</svg>' +
                        '</div>' +
                        '<h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h3>' +
                        '<p class="text-gray-600">Redirecting you to your dashboard...</p>' +
                        '</div>';
                    
                    setTimeout(function() {
                        window.location.href = '{{ route("dashboard") }}?payment=success';
                    }, 2000);
                }
                
                if (data.name === 'checkout.closed') {
                    console.log('Checkout was closed');
                    document.getElementById('checkout-container').innerHTML = 
                        '<div class="py-12 text-center">' +
                        '<div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">' +
                        '<svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' +
                        '</svg>' +
                        '</div>' +
                        '<h3 class="text-lg font-medium text-gray-900 mb-2">Checkout Closed</h3>' +
                        '<p class="text-gray-600 mb-6">No worries, you can try again anytime.</p>' +
                        '<a href="{{ route("pricing") }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">' +
                        'Return to Pricing' +
                        '</a>' +
                        '</div>';
                }

                if (data.name === 'checkout.error') {
                    console.error('Checkout error:', data);
                    document.getElementById('checkout-container').innerHTML = 
                        '<div class="py-12 text-center">' +
                        '<div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">' +
                        '<svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>' +
                        '</svg>' +
                        '</div>' +
                        '<h3 class="text-lg font-medium text-gray-900 mb-2">Something Went Wrong</h3>' +
                        '<p class="text-gray-600 mb-6">Please try again or contact support if the problem persists.</p>' +
                        '<a href="{{ route("pricing") }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">' +
                        'Try Again' +
                        '</a>' +
                        '</div>';
                }
            }
        });

        // Get transaction ID from checkout URL
        const checkoutUrl = '{{ $checkout_url }}';
        const urlParams = new URLSearchParams(checkoutUrl.split('?')[1]);
        const transactionId = urlParams.get('_ptxn');
        
        console.log('Checkout URL:', checkoutUrl);
        console.log('Transaction ID:', transactionId);

        function openCheckout() {
            if (!transactionId) {
                document.getElementById('checkout-container').innerHTML = 
                    '<div class="py-12 text-center">' +
                    '<p class="text-red-600 mb-4 text-lg font-medium">Invalid transaction ID</p>' +
                    '<a href="{{ route("pricing") }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">' +
                    'Return to Pricing' +
                    '</a>' +
                    '</div>';
                return;
            }

            try {
                Paddle.Checkout.open({
                    transactionId: transactionId,
                    settings: {
                        displayMode: 'overlay',
                        theme: 'light',
                        locale: 'en'
                    }
                });
            } catch (error) {
                console.error('Error opening Paddle checkout:', error);
                document.getElementById('open-checkout-manual').classList.remove('hidden');
                document.getElementById('checkout-container').innerHTML = 
                    '<div class="py-12 text-center">' +
                    '<p class="text-gray-700 mb-2 font-medium">Unable to open checkout automatically</p>' +
                    '<p class="text-gray-500 text-sm">Please click the button below to continue</p>' +
                    '</div>';
            }
        }

        // Auto-open checkout after page loads
        window.addEventListener('load', function() {
            setTimeout(openCheckout, 500);
        });

        // Manual trigger button
        document.getElementById('open-checkout-manual').addEventListener('click', openCheckout);
        
        // Show manual button after 3 seconds as fallback
        setTimeout(function() {
            document.getElementById('open-checkout-manual').classList.remove('hidden');
        }, 3000);
    </script>
</x-app-layout>