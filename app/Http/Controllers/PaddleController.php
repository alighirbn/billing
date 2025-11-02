<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Models\User;
use Exception;

class PaddleController extends Controller
{
    private function api(): string
    {
        return env('PADDLE_ENV') === 'sandbox'
            ? 'https://sandbox-api.paddle.com'
            : 'https://api.paddle.com';
    }

    public function pricing()
    {
        return view('pricing');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        $plan = $request->input('plan');
        $user = $request->user();

        $plans = [
            'monthly' => env('PADDLE_MONTHLY_PRICE_ID'),
            'annual'  => env('PADDLE_ANNUAL_PRICE_ID'),
        ];

        if (empty($plans[$plan])) {
            return back()->withErrors('Invalid plan selected.');
        }

        try {
            // Try to get or create Paddle customer
            $customerId = $this->ensurePaddleCustomer($user);

            // Build transaction payload
            $payload = [
                'items' => [[
                    'price_id' => $plans[$plan],
                    'quantity' => 1,
                ]],
            ];

            // Add customer_id if we have one
            if ($customerId) {
                $payload['customer_id'] = $customerId;
            }

            Log::info('Creating Paddle transaction', [
                'user_id' => $user->id,
                'plan' => $plan,
                'price_id' => $plans[$plan],
                'customer_id' => $customerId,
            ]);

            // Create transaction
            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post($this->api() . '/transactions', $payload);

            Log::info('Paddle transaction response', [
                'status' => $response->status(),
                'success' => $response->successful(),
                'body' => $response->json(),
            ]);

            if ($response->failed()) {
                $error = $response->json();
                $detail = $error['error']['detail'] ?? $error['error']['message'] ?? 'Request failed';
                
                Log::error('Paddle transaction failed', [
                    'error' => $error,
                    'user_id' => $user->id,
                ]);

                return back()->withErrors('Unable to create checkout: ' . $detail);
            }

            $data = $response->json('data');

            if (empty($data)) {
                Log::error('Empty response from Paddle', ['response' => $response->json()]);
                return back()->withErrors('Invalid response from Paddle. Please try again.');
            }

            // Get checkout URL
            $checkoutUrl = $data['checkout']['url'] ?? null;

            if (!$checkoutUrl) {
                Log::error('No checkout URL in response', ['data' => $data]);
                return back()->withErrors('Unable to generate checkout. Please try again.');
            }

            Log::info('Checkout URL generated', [
                'url' => $checkoutUrl,
                'transaction_id' => $data['id'] ?? null,
            ]);

            // Save subscription as pending
            Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan' => $plan,
                    'status' => 'pending',
                    'paddle_customer_id' => $customerId,
                    'meta' => [
                        'transaction_id' => $data['id'] ?? null,
                        'created_at' => now()->toISOString(),
                    ],
                ]
            );

            // Return checkout view with URL
            return view('checkout', [
                'checkout_url' => $checkoutUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('Paddle checkout exception', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        Log::info('Paddle webhook received', [
            'event_type' => $request->input('event_type'),
            'ip' => $request->ip(),
        ]);

        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid Paddle webhook signature', [
                'ip' => $request->ip(),
                'signature' => $request->header('Paddle-Signature'),
            ]);
            return response('Invalid signature', 403);
        }

        $eventType = $request->input('event_type');
        $data = $request->input('data', []);

        Log::info('Processing Paddle webhook', [
            'event_type' => $eventType,
            'customer_id' => $data['customer_id'] ?? null,
            'subscription_id' => $data['id'] ?? null,
        ]);

        try {
            switch ($eventType) {
                case 'transaction.completed':
                case 'transaction.paid':
                    $this->handleTransactionCompleted($data);
                    break;

                case 'subscription.created':
                case 'subscription.activated':
                case 'subscription.updated':
                    $this->handleSubscriptionActivated($data);
                    break;

                case 'subscription.canceled':
                    $this->handleSubscriptionCanceled($data);
                    break;

                case 'subscription.past_due':
                    $this->handleSubscriptionPastDue($data);
                    break;

                default:
                    Log::info('Unhandled Paddle webhook event', ['event' => $eventType]);
            }
        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'event_type' => $eventType,
                'error' => $e->getMessage(),
            ]);
            return response('Error', 500);
        }

        return response('OK', 200);
    }

    private function handleTransactionCompleted($data)
    {
        $customerId = $data['customer_id'] ?? null;
        
        if (!$customerId) {
            Log::warning('Transaction without customer_id', ['transaction_id' => $data['id'] ?? null]);
            return;
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        
        if (!$user) {
            Log::warning('Customer not found', ['customer_id' => $customerId]);
            return;
        }

        $subscriptionId = $data['subscription_id'] ?? null;
        
        if ($subscriptionId) {
            $priceId = $data['items'][0]['price']['id'] ?? '';
            $plan = $this->detectPlanFromPrice($priceId);

            Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'paddle_subscription_id' => $subscriptionId,
                    'paddle_customer_id' => $customerId,
                    'plan' => $plan,
                    'status' => 'active',
                    'meta' => $data,
                ]
            );

            Log::info('Subscription activated', [
                'user_id' => $user->id,
                'subscription_id' => $subscriptionId,
            ]);
        }
    }

    private function handleSubscriptionActivated($data)
    {
        $customerId = $data['customer_id'] ?? null;
        $subscriptionId = $data['id'] ?? null;

        if (!$customerId || !$subscriptionId) {
            return;
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        
        if (!$user) {
            Log::warning('Subscription for unknown customer', ['customer_id' => $customerId]);
            return;
        }

        $priceId = $data['items'][0]['price']['id'] ?? '';
        $plan = $this->detectPlanFromPrice($priceId);

        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'paddle_subscription_id' => $subscriptionId,
                'paddle_customer_id' => $customerId,
                'plan' => $plan,
                'status' => $data['status'] ?? 'active',
                'current_period_start' => $data['current_billing_period']['starts_at'] ?? now(),
                'current_period_end' => $data['current_billing_period']['ends_at'] ?? now()->addMonth(),
                'meta' => $data,
            ]
        );

        Log::info('Subscription updated', [
            'user_id' => $user->id,
            'subscription_id' => $subscriptionId,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    private function handleSubscriptionCanceled($data)
    {
        $subscriptionId = $data['id'] ?? null;
        
        if ($subscriptionId) {
            Subscription::where('paddle_subscription_id', $subscriptionId)
                ->update([
                    'status' => 'canceled',
                    'canceled_at' => now(),
                ]);

            Log::info('Subscription canceled', ['subscription_id' => $subscriptionId]);
        }
    }

    private function handleSubscriptionPastDue($data)
    {
        $subscriptionId = $data['id'] ?? null;
        
        if ($subscriptionId) {
            Subscription::where('paddle_subscription_id', $subscriptionId)
                ->update(['status' => 'past_due']);

            Log::info('Subscription past due', ['subscription_id' => $subscriptionId]);
        }
    }

    private function ensurePaddleCustomer(User $user): ?string
    {
        // Return existing customer ID if available
        if ($user->paddle_customer_id) {
            Log::info('Using existing customer', [
                'user_id' => $user->id,
                'customer_id' => $user->paddle_customer_id,
            ]);
            return $user->paddle_customer_id;
        }

        // Try to create customer
        try {
            $payload = [
                'email' => $user->email,
            ];

            if ($user->name) {
                $payload['name'] = $user->name;
            }

            Log::info('Creating Paddle customer', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post($this->api() . '/customers', $payload);

            if ($response->successful()) {
                $customerId = $response->json('data.id');
                
                if ($customerId) {
                    $user->update(['paddle_customer_id' => $customerId]);
                    
                    Log::info('Customer created', [
                        'user_id' => $user->id,
                        'customer_id' => $customerId,
                    ]);
                    
                    return $customerId;
                }
            }

            Log::warning('Customer creation failed, continuing without customer_id', [
                'user_id' => $user->id,
                'error' => $response->json('error'),
            ]);

        } catch (Exception $e) {
            Log::warning('Exception creating customer', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('Paddle-Signature');
        $secret = env('PADDLE_WEBHOOK_SECRET');

        if (!$signature || !$secret) {
            return false;
        }

        $ts = null;
        $h1 = null;
        
        foreach (array_filter(explode(';', $signature)) as $el) {
            $el = trim($el);
            if (str_starts_with($el, 'ts=')) {
                $ts = substr($el, 3);
            }
            if (str_starts_with($el, 'h1=')) {
                $h1 = substr($el, 3);
            }
        }

        if (!$ts || !$h1) {
            return false;
        }

        // Check timestamp (not older than 5 minutes)
        if (abs(time() - (int)$ts) > 300) {
            return false;
        }

        $payload = $request->getContent();
        $signedPayload = $ts . ':' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $h1);
    }

    private function detectPlanFromPrice(string $priceId): string
    {
        if ($priceId === env('PADDLE_MONTHLY_PRICE_ID')) {
            return 'monthly';
        }
        if ($priceId === env('PADDLE_ANNUAL_PRICE_ID')) {
            return 'annual';
        }
        return 'unknown';
    }
}