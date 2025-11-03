<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaddleController;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;


use Illuminate\Support\Facades\Log;



// Public home
Route::get('/', function () {
    return view('welcome');
});

// Paddle webhook (no auth, no csrf)
Route::post('/paddle/webhook', [PaddleController::class, 'webhook'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('paddle.webhook');

// Authenticated area
Route::middleware(['auth'])->group(function () {

    // Pricing page
    Route::get('/pricing', [PaddleController::class, 'pricing'])
        ->name('pricing');

    // Prevent duplicate rendering: redirect GET /checkout to pricing
    Route::get('/checkout', function () {
        return redirect()->route('pricing');
    })->name('checkout.view');

    // Paddle checkout POST handler
    Route::post('/checkout', [PaddleController::class, 'checkout'])
        ->name('checkout');

    // Dashboard (user can always access, even while pending)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Routes that require active subscription
    Route::middleware(['subscribed'])->group(function () {
        Route::get('/app', function () {
            return view('app.home');
        })->name('app.home');
    });

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    
    // Subscription Management Routes
    Route::get('/subscription/manage', function () {
        $user = auth()->user();
        $subscription = $user->subscription;
        
        if (!$subscription) {
            return redirect()->route('pricing')
                ->with('error', 'No subscription found. Please subscribe first.');
        }
        
        return view('subscription.manage', compact('subscription'));
    })->name('subscription.manage');
    
    
    // Cancel Subscription
    Route::post('/subscription/cancel', function (Illuminate\Http\Request $request) {
        $user = auth()->user();
        $subscription = $user->subscription;
        
        if (!$subscription || $subscription->status !== 'active') {
            return back()->with('error', 'No active subscription found.');
        }
        
        if (!$subscription->paddle_subscription_id) {
            return back()->with('error', 'Cannot cancel: No Paddle subscription ID found.');
        }
        
        try {
            $api = env('PADDLE_ENV') === 'sandbox'
                ? 'https://sandbox-api.paddle.com'
                : 'https://api.paddle.com';
            
            // Cancel the subscription in Paddle
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post("{$api}/subscriptions/{$subscription->paddle_subscription_id}/cancel", [
                    'effective_from' => 'next_billing_period', // or 'immediately'
                ]);
            
            if ($response->successful()) {
                // Update local database
                $subscription->update([
                    'status' => 'canceled',
                    'canceled_at' => now(),
                ]);
                
                Log::info('Subscription canceled', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->paddle_subscription_id,
                ]);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Your subscription has been canceled. It will remain active until the end of your billing period.');
            } else {
                $error = $response->json('error.detail') ?? 'Failed to cancel subscription';
                Log::error('Failed to cancel subscription', [
                    'user_id' => $user->id,
                    'error' => $response->json(),
                ]);
                
                return back()->with('error', 'Failed to cancel subscription: ' . $error);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception canceling subscription', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'An error occurred while canceling your subscription.');
        }
    })->name('subscription.cancel');
    
    
    // Update Subscription (Change Plan)
    Route::post('/subscription/update', function (Illuminate\Http\Request $request) {
        $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);
        
        $user = auth()->user();
        $subscription = $user->subscription;
        
        if (!$subscription || $subscription->status !== 'active') {
            return back()->with('error', 'No active subscription found.');
        }
        
        if (!$subscription->paddle_subscription_id) {
            return back()->with('error', 'Cannot update: No Paddle subscription ID found.');
        }
        
        $newPlan = $request->input('plan');
        
        // Check if already on this plan
        if ($subscription->plan === $newPlan) {
            return back()->with('info', 'You are already on the ' . $newPlan . ' plan.');
        }
        
        try {
            $api = env('PADDLE_ENV') === 'sandbox'
                ? 'https://sandbox-api.paddle.com'
                : 'https://api.paddle.com';
            
            $newPriceId = $newPlan === 'monthly' 
                ? env('PADDLE_MONTHLY_PRICE_ID')
                : env('PADDLE_ANNUAL_PRICE_ID');
            
            // Update subscription in Paddle
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->patch("{$api}/subscriptions/{$subscription->paddle_subscription_id}", [
                    'items' => [
                        [
                            'price_id' => $newPriceId,
                            'quantity' => 1,
                        ],
                    ],
                    'proration_billing_mode' => 'prorated_immediately', // or 'full_immediately'
                ]);
            
            if ($response->successful()) {
                // Update local database
                $subscription->update([
                    'plan' => $newPlan,
                    'meta' => $response->json('data'),
                ]);
                
                Log::info('Subscription updated', [
                    'user_id' => $user->id,
                    'old_plan' => $subscription->plan,
                    'new_plan' => $newPlan,
                ]);
                
                return redirect()->route('subscription.manage')
                    ->with('success', 'Your subscription has been updated to the ' . $newPlan . ' plan!');
            } else {
                $error = $response->json('error.detail') ?? 'Failed to update subscription';
                Log::error('Failed to update subscription', [
                    'user_id' => $user->id,
                    'error' => $response->json(),
                ]);
                
                return back()->with('error', 'Failed to update subscription: ' . $error);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception updating subscription', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'An error occurred while updating your subscription.');
        }
    })->name('subscription.update');
    
    
    // Reactivate Canceled Subscription
    Route::post('/subscription/reactivate', function () {
        $user = auth()->user();
        $subscription = $user->subscription;
        
        if (!$subscription || $subscription->status !== 'canceled') {
            return back()->with('error', 'No canceled subscription found.');
        }
        
        if (!$subscription->paddle_subscription_id) {
            return back()->with('error', 'Cannot reactivate: No Paddle subscription ID found.');
        }
        
        try {
            $api = env('PADDLE_ENV') === 'sandbox'
                ? 'https://sandbox-api.paddle.com'
                : 'https://api.paddle.com';
            
            // Resume the subscription in Paddle
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post("{$api}/subscriptions/{$subscription->paddle_subscription_id}/resume", [
                    'effective_from' => 'immediately',
                ]);
            
            if ($response->successful()) {
                // Update local database
                $subscription->update([
                    'status' => 'active',
                    'canceled_at' => null,
                ]);
                
                Log::info('Subscription reactivated', [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->paddle_subscription_id,
                ]);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Your subscription has been reactivated!');
            } else {
                $error = $response->json('error.detail') ?? 'Failed to reactivate subscription';
                return back()->with('error', 'Failed to reactivate subscription: ' . $error);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception reactivating subscription', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'An error occurred while reactivating your subscription.');
        }
    })->name('subscription.reactivate');
    
    
    // Update Payment Method
    Route::get('/subscription/update-payment', function () {
        $user = auth()->user();
        $subscription = $user->subscription;
        
        if (!$subscription || !$subscription->paddle_subscription_id) {
            return back()->with('error', 'No active subscription found.');
        }
        
        try {
            $api = env('PADDLE_ENV') === 'sandbox'
                ? 'https://sandbox-api.paddle.com'
                : 'https://api.paddle.com';
            
            // Get update payment method URL from Paddle
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->get("{$api}/subscriptions/{$subscription->paddle_subscription_id}/update-payment-method-transaction");
            
            if ($response->successful()) {
                $checkoutUrl = $response->json('data.checkout.url');
                
                if ($checkoutUrl) {
                    return redirect($checkoutUrl);
                } else {
                    return back()->with('error', 'Could not generate payment update URL.');
                }
            } else {
                return back()->with('error', 'Failed to get payment update URL.');
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating payment method.');
        }
    })->name('subscription.update-payment');
    
    
    // Billing History
    Route::get('/subscription/billing-history', function () {
        $user = auth()->user();
        
        if (!$user->paddle_customer_id) {
            return back()->with('error', 'No customer ID found.');
        }
        
        try {
            $api = env('PADDLE_ENV') === 'sandbox'
                ? 'https://sandbox-api.paddle.com'
                : 'https://api.paddle.com';
            
            // Get transactions for this customer
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->get("{$api}/transactions", [
                    'customer_id' => $user->paddle_customer_id,
                ]);
            
            $transactions = [];
            if ($response->successful()) {
                $transactions = $response->json('data', []);
            }
            
            return view('subscription.billing-history', compact('transactions'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load billing history.');
        }
    })->name('subscription.billing-history');
});







Route::get('/fix-paddle-customer-id', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'Please login first';
    }
    
    $api = env('PADDLE_ENV') === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    // Search for customer by email
    $response = Http::withToken(env('PADDLE_API_KEY'))
        ->withHeaders(['Paddle-Version' => '1'])
        ->get("{$api}/customers", [
            'email' => $user->email,
        ]);
    
    if ($response->successful()) {
        $customers = $response->json('data', []);
        
        if (!empty($customers)) {
            $customerId = $customers[0]['id'];
            
            // Update user
            $user->update(['paddle_customer_id' => $customerId]);
            
            // Update subscription if exists
            $subscription = \App\Models\Subscription::where('user_id', $user->id)->first();
            if ($subscription) {
                $subscription->update(['paddle_customer_id' => $customerId]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Customer ID fixed!',
                'customer_id' => $customerId,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'paddle_customer_id' => $user->paddle_customer_id,
                ],
                'subscription' => $subscription ? [
                    'id' => $subscription->id,
                    'status' => $subscription->status,
                    'paddle_customer_id' => $subscription->paddle_customer_id,
                ] : null,
            ], 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No customer found in Paddle with email: ' . $user->email,
            ]);
        }
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to search for customer',
            'error' => $response->json(),
        ]);
    }
})->middleware('auth')->name('fix.customer');





















// Add to routes/web.php
Route::get('/test-paddle', function () {
    $response = Http::withToken(env('PADDLE_API_KEY'))
        ->withHeaders(['Paddle-Version' => '1'])
        ->get('https://sandbox-api.paddle.com/customers?per_page=1');
    
    if ($response->successful()) {
        return 'API Key is working! ✅';
    }
    
    return 'API Key failed: ' . $response->json('error.detail');
});

Route::get('/test-paddle-price', function () {
    $api = env('PADDLE_ENV') === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    $priceId = env('PADDLE_MONTHLY_PRICE_ID');
    
    $response = Http::withToken(env('PADDLE_API_KEY'))
        ->withHeaders(['Paddle-Version' => '1'])
        ->get("{$api}/prices/{$priceId}");
    
    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'data' => $response->json(),
    ]);
})->middleware('auth');

Route::get('/paddle-env-check', function () {
    $apiKey = env('PADDLE_API_KEY');
    $apiKeyPrefix = substr($apiKey, 0, 20);
    
    $isSandboxKey = str_starts_with($apiKey, 'pdl_sdbx_');
    $isLiveKey = str_starts_with($apiKey, 'pdl_live_');
    $paddleEnv = env('PADDLE_ENV');
    
    $api = $paddleEnv === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    // Check monthly price
    $monthlyPriceId = env('PADDLE_MONTHLY_PRICE_ID');
    $monthlyCheck = Http::withToken($apiKey)
        ->withHeaders(['Paddle-Version' => '1'])
        ->get("{$api}/prices/{$monthlyPriceId}");
    
    // Check annual price
    $annualPriceId = env('PADDLE_ANNUAL_PRICE_ID');
    $annualCheck = Http::withToken($apiKey)
        ->withHeaders(['Paddle-Version' => '1'])
        ->get("{$api}/prices/{$annualPriceId}");
    
    // Try to create a test transaction
    $transactionTest = Http::withToken($apiKey)
        ->withHeaders(['Paddle-Version' => '1'])
        ->post("{$api}/transactions", [
            'items' => [[
                'price_id' => $monthlyPriceId,
                'quantity' => 1,
            ]],
        ]);
    
    $checkoutUrl = $transactionTest->json('data.checkout.url') ?? null;
    
    return response()->json([
        'environment' => [
            'PADDLE_ENV' => $paddleEnv,
            'api_key_type' => $isSandboxKey ? 'SANDBOX' : ($isLiveKey ? 'LIVE' : 'UNKNOWN'),
            'api_key_prefix' => $apiKeyPrefix . '...',
            'api_url' => $api,
            'mismatch' => ($paddleEnv === 'sandbox' && $isLiveKey) || ($paddleEnv === 'production' && $isSandboxKey),
        ],
        'monthly_price' => [
            'id' => $monthlyPriceId,
            'status' => $monthlyCheck->status(),
            'valid' => $monthlyCheck->successful(),
            'name' => $monthlyCheck->json('data.description'),
            'amount' => $monthlyCheck->json('data.unit_price.amount'),
            'currency' => $monthlyCheck->json('data.unit_price.currency_code'),
        ],
        'annual_price' => [
            'id' => $annualPriceId,
            'status' => $annualCheck->status(),
            'valid' => $annualCheck->successful(),
            'name' => $annualCheck->json('data.description'),
            'amount' => $annualCheck->json('data.unit_price.amount'),
            'currency' => $annualCheck->json('data.unit_price.currency_code'),
        ],
        'transaction_test' => [
            'status' => $transactionTest->status(),
            'success' => $transactionTest->successful(),
            'checkout_url' => $checkoutUrl,
            'transaction_id' => $transactionTest->json('data.id'),
            'error' => $transactionTest->failed() ? $transactionTest->json('error') : null,
        ],
        'recommendations' => [
            'env_mismatch' => ($paddleEnv === 'sandbox' && $isLiveKey) 
                ? "⚠️ WARNING: Using LIVE API key but PADDLE_ENV is 'sandbox'. Set PADDLE_ENV=production"
                : (($paddleEnv === 'production' && $isSandboxKey) 
                    ? "⚠️ WARNING: Using SANDBOX API key but PADDLE_ENV is 'production'. Set PADDLE_ENV=sandbox"
                    : "✅ Environment and API key match"),
            'monthly_price' => $monthlyCheck->successful() ? "✅ Monthly price is valid" : "❌ Monthly price not found",
            'annual_price' => $annualCheck->successful() ? "✅ Annual price is valid" : "❌ Annual price not found",
            'transaction' => $transactionTest->successful() ? "✅ Can create transactions" : "❌ Cannot create transactions",
            'checkout_url' => $checkoutUrl ? "✅ Checkout URL generated: " . $checkoutUrl : "❌ No checkout URL",
        ],
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');
require __DIR__ . '/auth.php';

// Add these routes to your routes/web.php file


// ==========================================
// EMERGENCY FIX ROUTE - ACTIVATE SUBSCRIPTION MANUALLY
// ==========================================
Route::get('/emergency-activate', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'Please login first';
    }
    
    $subscription = Subscription::where('user_id', $user->id)->first();
    
    if (!$subscription) {
        return 'No subscription found. Creating one...';
    }
    
    // Force activate the subscription
    $subscription->update([
        'status' => 'active',
        'current_period_start' => now(),
        'current_period_end' => now()->addMonth(),
    ]);
    
    return redirect()->route('dashboard')->with('success', 'Subscription manually activated!');
})->middleware('auth')->name('emergency.activate');


// ==========================================
// DIAGNOSTIC ROUTE - CHECK EVERYTHING
// ==========================================
Route::get('/paddle-diagnostic', function () {
    $user = auth()->user();
    
    if (!$user) {
        return response()->json(['error' => 'Please login first']);
    }
    
    $subscription = Subscription::where('user_id', $user->id)->first();
    
    $api = env('PADDLE_ENV') === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    $diagnostics = [
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'paddle_customer_id' => $user->paddle_customer_id,
        ],
        'subscription' => $subscription ? [
            'id' => $subscription->id,
            'status' => $subscription->status,
            'plan' => $subscription->plan,
            'paddle_customer_id' => $subscription->paddle_customer_id,
            'paddle_subscription_id' => $subscription->paddle_subscription_id,
            'current_period_start' => $subscription->current_period_start,
            'current_period_end' => $subscription->current_period_end,
            'meta' => $subscription->meta,
        ] : null,
        'env_config' => [
            'PADDLE_ENV' => env('PADDLE_ENV'),
            'PADDLE_API_KEY' => substr(env('PADDLE_API_KEY'), 0, 20) . '...',
            'PADDLE_WEBHOOK_SECRET' => env('PADDLE_WEBHOOK_SECRET') ? 'Set ✓' : 'Missing ✗',
            'PADDLE_MONTHLY_PRICE_ID' => env('PADDLE_MONTHLY_PRICE_ID'),
            'PADDLE_ANNUAL_PRICE_ID' => env('PADDLE_ANNUAL_PRICE_ID'),
            'api_url' => $api,
        ],
    ];
    
    // Check if we can find customer in Paddle
    if ($user->paddle_customer_id) {
        $customerCheck = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->get("{$api}/customers/{$user->paddle_customer_id}");
        
        $diagnostics['paddle_customer'] = [
            'found' => $customerCheck->successful(),
            'status' => $customerCheck->status(),
            'data' => $customerCheck->json(),
        ];
        
        // Check for subscriptions
        $subsCheck = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->get("{$api}/subscriptions", [
                'customer_id' => $user->paddle_customer_id,
            ]);
        
        $diagnostics['paddle_subscriptions'] = [
            'found' => $subsCheck->successful(),
            'status' => $subsCheck->status(),
            'data' => $subsCheck->json(),
        ];
    } else {
        $diagnostics['paddle_customer'] = 'No paddle_customer_id on user';
        
        // Try to search by email
        $customerSearch = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->get("{$api}/customers", [
                'email' => $user->email,
            ]);
        
        $diagnostics['customer_search_by_email'] = [
            'found' => $customerSearch->successful(),
            'status' => $customerSearch->status(),
            'data' => $customerSearch->json(),
        ];
    }
    
    return response()->json($diagnostics, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth')->name('paddle.diagnostic');


// ==========================================
// SYNC FROM PADDLE - FETCH REAL DATA
// ==========================================
Route::get('/paddle-sync', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'Please login first';
    }
    
    $api = env('PADDLE_ENV') === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    $results = ['steps' => []];
    
    // Step 1: Find or create customer
    if (!$user->paddle_customer_id) {
        $results['steps'][] = 'No paddle_customer_id on user, searching by email...';
        
        $search = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->get("{$api}/customers", ['email' => $user->email]);
        
        if ($search->successful()) {
            $customers = $search->json('data', []);
            if (!empty($customers)) {
                $customerId = $customers[0]['id'];
                $user->update(['paddle_customer_id' => $customerId]);
                $results['steps'][] = "Found customer in Paddle: {$customerId}";
            } else {
                $results['steps'][] = 'No customer found in Paddle for this email';
            }
        } else {
            $results['steps'][] = 'Failed to search customers: ' . $search->json('error.detail');
        }
    } else {
        $results['steps'][] = "User already has paddle_customer_id: {$user->paddle_customer_id}";
    }
    
    // Step 2: Find subscriptions
    if ($user->paddle_customer_id) {
        $results['steps'][] = 'Fetching subscriptions from Paddle...';
        
        $subsResponse = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->get("{$api}/subscriptions", [
                'customer_id' => $user->paddle_customer_id,
            ]);
        
        if ($subsResponse->successful()) {
            $subscriptions = $subsResponse->json('data', []);
            $results['steps'][] = 'Found ' . count($subscriptions) . ' subscription(s) in Paddle';
            
            if (!empty($subscriptions)) {
                $paddleSub = $subscriptions[0]; // Get first subscription
                
                // Detect plan
                $priceId = $paddleSub['items'][0]['price']['id'] ?? '';
                $plan = 'monthly';
                if ($priceId === env('PADDLE_ANNUAL_PRICE_ID')) {
                    $plan = 'annual';
                } elseif ($priceId === env('PADDLE_MONTHLY_PRICE_ID')) {
                    $plan = 'monthly';
                }
                
                // Update or create subscription
                Subscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'paddle_subscription_id' => $paddleSub['id'],
                        'paddle_customer_id' => $user->paddle_customer_id,
                        'plan' => $plan,
                        'status' => $paddleSub['status'],
                        'current_period_start' => $paddleSub['current_billing_period']['starts_at'] ?? now(),
                        'current_period_end' => $paddleSub['current_billing_period']['ends_at'] ?? now()->addMonth(),
                        'meta' => $paddleSub,
                    ]
                );
                
                $results['steps'][] = "✅ Subscription synced! Status: {$paddleSub['status']}, Plan: {$plan}";
                $results['subscription'] = $paddleSub;
            } else {
                $results['steps'][] = '⚠️ No active subscriptions found in Paddle';
            }
        } else {
            $results['steps'][] = 'Failed to fetch subscriptions: ' . $subsResponse->json('error.detail');
        }
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth')->name('paddle.sync');


// ==========================================
// TEST WEBHOOK MANUALLY
// ==========================================
Route::get('/test-webhook', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'Please login first';
    }
    
    // Simulate a transaction.completed webhook
    $fakeWebhookData = [
        'event_type' => 'transaction.completed',
        'data' => [
            'id' => 'txn_test_' . time(),
            'customer_id' => $user->paddle_customer_id ?: 'ctm_test_' . $user->id,
            'subscription_id' => 'sub_test_' . $user->id,
            'status' => 'completed',
            'items' => [
                [
                    'price' => [
                        'id' => env('PADDLE_MONTHLY_PRICE_ID'),
                    ],
                    'quantity' => 1,
                ],
            ],
            'billing_period' => [
                'starts_at' => now()->toISOString(),
                'ends_at' => now()->addMonth()->toISOString(),
            ],
        ],
    ];
    
    // Call the webhook handler directly
    $controller = new \App\Http\Controllers\PaddleController();
    
    try {
        // Create a fake request
        $request = Request::create('/paddle/webhook', 'POST', $fakeWebhookData);
        
        // Process it
        $response = $controller->webhook($request);
        
        return response()->json([
            'message' => 'Webhook test completed',
            'response_status' => $response->getStatusCode(),
            'subscription' => Subscription::where('user_id', $user->id)->first(),
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->middleware('auth')->name('test.webhook');


// ==========================================
// FORCE ACTIVATE (EMERGENCY)
// ==========================================
Route::get('/force-activate/{plan}', function ($plan) {
    $user = auth()->user();
    
    if (!$user) {
        return 'Please login first';
    }
    
    if (!in_array($plan, ['monthly', 'annual'])) {
        return 'Invalid plan. Use: /force-activate/monthly or /force-activate/annual';
    }
    
    Subscription::updateOrCreate(
        ['user_id' => $user->id],
        [
            'plan' => $plan,
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => $plan === 'monthly' ? now()->addMonth() : now()->addYear(),
            'meta' => [
                'manually_activated' => true,
                'activated_at' => now()->toISOString(),
            ],
        ]
    );
    
    return redirect()->route('dashboard')
        ->with('success', "Subscription force-activated with {$plan} plan!");
        
})->middleware('auth')->name('force.activate');


// ==========================================
// VIEW ALL SUBSCRIPTIONS (DEBUG)
// ==========================================
Route::get('/view-subscriptions', function () {
    $subscriptions = Subscription::with('user')->get();
    
    return response()->json([
        'total' => $subscriptions->count(),
        'subscriptions' => $subscriptions->map(function ($sub) {
            return [
                'id' => $sub->id,
                'user_id' => $sub->user_id,
                'user_email' => $sub->user->email ?? 'N/A',
                'status' => $sub->status,
                'plan' => $sub->plan,
                'paddle_customer_id' => $sub->paddle_customer_id,
                'paddle_subscription_id' => $sub->paddle_subscription_id,
                'created_at' => $sub->created_at,
            ];
        }),
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');


// Add this to routes/web.php to debug webhook issues


// ==========================================
// CHECK WEBHOOK LOGS
// ==========================================
Route::get('/check-webhook-logs', function () {
    // Read last 100 lines of Laravel log
    $logFile = storage_path('logs/laravel.log');
    
    if (!file_exists($logFile)) {
        return response()->json(['error' => 'Log file not found']);
    }
    
    $lines = [];
    $file = new SplFileObject($logFile);
    $file->seek(PHP_INT_MAX);
    $lastLine = $file->key();
    $startLine = max(0, $lastLine - 200);
    
    $file->seek($startLine);
    while (!$file->eof()) {
        $line = $file->current();
        if (stripos($line, 'paddle') !== false || stripos($line, 'webhook') !== false) {
            $lines[] = $line;
        }
        $file->next();
    }
    
    return response()->json([
        'recent_paddle_logs' => $lines,
        'total_lines' => count($lines),
        'hint' => 'Look for errors like "Invalid signature", "User not found", etc.'
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');


// ==========================================
// TEST WEBHOOK SIGNATURE
// ==========================================
Route::get('/test-webhook-signature', function () {
    $secret = env('PADDLE_WEBHOOK_SECRET');
    
    if (!$secret) {
        return response()->json([
            'error' => 'PADDLE_WEBHOOK_SECRET not set in .env',
            'solution' => 'Get it from Paddle Dashboard → Developer Tools → Notifications → Notification Destination → Webhook secret'
        ]);
    }
    
    // Simulate a webhook payload
    $timestamp = time();
    $payload = json_encode([
        'event_type' => 'transaction.completed',
        'data' => ['test' => true]
    ]);
    
    $signedPayload = $timestamp . ':' . $payload;
    $signature = hash_hmac('sha256', $signedPayload, $secret);
    
    return response()->json([
        'webhook_secret_configured' => true,
        'secret_prefix' => substr($secret, 0, 15) . '...',
        'test_signature' => [
            'timestamp' => $timestamp,
            'payload' => $payload,
            'expected_signature' => $signature,
            'full_header_value' => "ts={$timestamp};h1={$signature}",
        ],
        'instructions' => [
            '1. Copy the full_header_value above',
            '2. Use it as Paddle-Signature header when testing webhook endpoint',
            '3. Send POST request to /paddle/webhook with the payload'
        ]
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');


// ==========================================
// CHECK PADDLE WEBHOOK CONFIGURATION
// ==========================================
Route::get('/check-paddle-webhook-config', function () {
    $api = env('PADDLE_ENV') === 'sandbox' 
        ? 'https://sandbox-api.paddle.com'
        : 'https://api.paddle.com';
    
    // Get notification settings from Paddle
    $response = Http::withToken(env('PADDLE_API_KEY'))
        ->withHeaders(['Paddle-Version' => '1'])
        ->get("{$api}/notification-settings");
    
    if ($response->failed()) {
        return response()->json([
            'error' => 'Failed to fetch notification settings from Paddle',
            'status' => $response->status(),
            'response' => $response->json(),
        ]);
    }
    
    $settings = $response->json('data', []);
    
    $appUrl = config('app.url');
    $expectedWebhookUrl = rtrim($appUrl, '/') . '/paddle/webhook';
    
    $analysis = [
        'paddle_notification_settings' => $settings,
        'your_app' => [
            'app_url' => $appUrl,
            'expected_webhook_url' => $expectedWebhookUrl,
        ],
        'issues' => [],
        'recommendations' => [],
    ];
    
    foreach ($settings as $setting) {
        $destination = $setting['destination']['url'] ?? '';
        $active = $setting['active'] ?? false;
        
        if (!$active) {
            $analysis['issues'][] = "Notification destination is INACTIVE: {$destination}";
        }
        
        if ($destination !== $expectedWebhookUrl) {
            $analysis['issues'][] = "Webhook URL mismatch. Paddle has: {$destination}, Expected: {$expectedWebhookUrl}";
            $analysis['recommendations'][] = "Update webhook URL in Paddle to: {$expectedWebhookUrl}";
        }
        
        if (stripos($destination, 'localhost') !== false || stripos($destination, '127.0.0.1') !== false) {
            $analysis['issues'][] = "Webhook URL points to localhost! Paddle cannot reach localhost.";
            $analysis['recommendations'][] = "Use ngrok or deploy to a public URL";
        }
    }
    
    if (empty($settings)) {
        $analysis['issues'][] = 'No notification settings configured in Paddle!';
        $analysis['recommendations'][] = 'Go to Paddle Dashboard → Developer Tools → Notifications and add your webhook URL';
    }
    
    if (empty($analysis['issues'])) {
        $analysis['status'] = '✅ Webhook configuration looks good!';
    } else {
        $analysis['status'] = '❌ Issues found with webhook configuration';
    }
    
    return response()->json($analysis, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');


// ==========================================
// SIMULATE WEBHOOK (NO SIGNATURE CHECK)
// ==========================================
Route::post('/simulate-webhook', function (Illuminate\Http\Request $request) {
    Log::info('=== SIMULATED WEBHOOK START ===');
    
    $eventType = $request->input('event_type', 'transaction.completed');
    $user = auth()->user();
    
    if (!$user) {
        return response()->json(['error' => 'Must be logged in']);
    }
    
    $fakeData = [
        'event_type' => $eventType,
        'data' => [
            'id' => 'txn_simulated_' . time(),
            'customer_id' => $user->paddle_customer_id ?: 'ctm_simulated_' . $user->id,
            'subscription_id' => 'sub_simulated_' . time(),
            'status' => 'completed',
            'items' => [
                [
                    'price' => [
                        'id' => env('PADDLE_MONTHLY_PRICE_ID'),
                    ],
                    'quantity' => 1,
                ],
            ],
            'billing_period' => [
                'starts_at' => now()->toISOString(),
                'ends_at' => now()->addMonth()->toISOString(),
            ],
        ],
    ];
    
    Log::info('Simulated webhook payload', $fakeData);
    
    try {
        $controller = new \App\Http\Controllers\PaddleController();
        
        // Create fake request WITHOUT signature check
        $fakeRequest = Illuminate\Http\Request::create(
            '/paddle/webhook',
            'POST',
            $fakeData
        );
        
        // Manually call the handler methods
        $data = $fakeData['data'];
        
        // Use reflection to call private method
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('handleTransactionCompleted');
        $method->setAccessible(true);
        $method->invoke($controller, $data);
        
        Log::info('=== SIMULATED WEBHOOK END ===');
        
        $subscription = \App\Models\Subscription::where('user_id', $user->id)->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Webhook simulated successfully',
            'subscription_after' => $subscription ? [
                'status' => $subscription->status,
                'plan' => $subscription->plan,
                'paddle_subscription_id' => $subscription->paddle_subscription_id,
            ] : null,
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        Log::error('Simulated webhook error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
})->middleware('auth');


// ==========================================
// FIX WEBHOOK SECRET IN ENV
// ==========================================
Route::get('/verify-env-config', function () {
    $checks = [
        'PADDLE_API_KEY' => [
            'value' => env('PADDLE_API_KEY'),
            'set' => !empty(env('PADDLE_API_KEY')),
            'format' => 'Should start with pdl_live_ or pdl_sdbx_',
            'valid' => str_starts_with(env('PADDLE_API_KEY'), 'pdl_'),
        ],
        'PADDLE_WEBHOOK_SECRET' => [
            'value' => env('PADDLE_WEBHOOK_SECRET') ? substr(env('PADDLE_WEBHOOK_SECRET'), 0, 20) . '...' : null,
            'set' => !empty(env('PADDLE_WEBHOOK_SECRET')),
            'format' => 'Should start with pdl_ntfset_',
            'valid' => str_starts_with(env('PADDLE_WEBHOOK_SECRET'), 'pdl_ntfset_'),
        ],
        'PADDLE_ENV' => [
            'value' => env('PADDLE_ENV'),
            'set' => !empty(env('PADDLE_ENV')),
            'format' => 'Should be "sandbox" or "production"',
            'valid' => in_array(env('PADDLE_ENV'), ['sandbox', 'production']),
        ],
        'PADDLE_MONTHLY_PRICE_ID' => [
            'value' => env('PADDLE_MONTHLY_PRICE_ID'),
            'set' => !empty(env('PADDLE_MONTHLY_PRICE_ID')),
            'format' => 'Should start with pri_',
            'valid' => str_starts_with(env('PADDLE_MONTHLY_PRICE_ID'), 'pri_'),
        ],
        'PADDLE_ANNUAL_PRICE_ID' => [
            'value' => env('PADDLE_ANNUAL_PRICE_ID'),
            'set' => !empty(env('PADDLE_ANNUAL_PRICE_ID')),
            'format' => 'Should start with pri_',
            'valid' => str_starts_with(env('PADDLE_ANNUAL_PRICE_ID'), 'pri_'),
        ],
        'APP_URL' => [
            'value' => env('APP_URL'),
            'set' => !empty(env('APP_URL')),
            'format' => 'Should be your public domain (not localhost in production)',
            'valid' => !str_contains(env('APP_URL'), 'localhost') || app()->environment('local'),
        ],
    ];
    
    $issues = [];
    $recommendations = [];
    
    foreach ($checks as $key => $check) {
        if (!$check['set']) {
            $issues[] = "{$key} is not set in .env";
            $recommendations[] = "Add {$key}=your_value to .env file";
        } elseif (!$check['valid']) {
            $issues[] = "{$key} has invalid format: {$check['value']}";
            $recommendations[] = "{$key} {$check['format']}";
        }
    }
    
    // Check if .env was updated but not loaded
    if (config('paddle.api_key') !== env('PADDLE_API_KEY')) {
        $issues[] = 'Config cache might be stale';
        $recommendations[] = 'Run: php artisan config:clear';
    }
    
    return response()->json([
        'environment_checks' => $checks,
        'issues' => $issues,
        'recommendations' => $recommendations,
        'status' => empty($issues) ? '✅ All environment variables configured correctly' : '❌ Configuration issues found',
        'next_steps' => empty($issues) ? [
            '1. Check webhook URL in Paddle Dashboard',
            '2. Replay failed webhooks',
            '3. Check webhook logs: /check-webhook-logs'
        ] : $recommendations,
    ], 200, [], JSON_PRETTY_PRINT);
})->middleware('auth');