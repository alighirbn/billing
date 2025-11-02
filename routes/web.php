<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaddleController;

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