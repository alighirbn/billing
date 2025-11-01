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
require __DIR__ . '/auth.php';