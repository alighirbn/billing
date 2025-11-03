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
            'full_payload' => $request->all(),
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
            'data' => $data,
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

            return response('OK', 200);

        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'event_type' => $eventType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    private function handleTransactionCompleted($data)
    {
        $customerId = $data['customer_id'] ?? null;
        
        if (!$customerId) {
            Log::warning('Transaction without customer_id', ['transaction_id' => $data['id'] ?? null]);
            return;
        }

        // First try to find by paddle_customer_id
        $user = User::where('paddle_customer_id', $customerId)->first();
        
        if (!$user) {
            Log::warning('User not found by paddle_customer_id, checking subscriptions', [
                'customer_id' => $customerId
            ]);
            
            // Try to find user through subscription
            $subscription = Subscription::where('paddle_customer_id', $customerId)->first();
            if ($subscription) {
                $user = $subscription->user;
                Log::info('Found user through subscription', ['user_id' => $user->id]);
            }
        }
        
        if (!$user) {
            Log::error('Cannot find user for customer', [
                'customer_id' => $customerId,
                'transaction_id' => $data['id'] ?? null,
            ]);
            return;
        }

        $subscriptionId = $data['subscription_id'] ?? null;
        $priceId = $data['items'][0]['price']['id'] ?? '';
        $plan = $this->detectPlanFromPrice($priceId);

        // Extract billing period if available
        $billingPeriod = $data['billing_period'] ?? [];
        
        $subscriptionData = [
            'paddle_customer_id' => $customerId,
            'plan' => $plan,
            'status' => 'active',
            'meta' => $data,
        ];

        if ($subscriptionId) {
            $subscriptionData['paddle_subscription_id'] = $subscriptionId;
        }

        if (!empty($billingPeriod['starts_at'])) {
            $subscriptionData['current_period_start'] = $billingPeriod['starts_at'];
        }

        if (!empty($billingPeriod['ends_at'])) {
            $subscriptionData['current_period_end'] = $billingPeriod['ends_at'];
        }

        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            $subscriptionData
        );

        Log::info('Subscription activated via transaction.completed', [
            'user_id' => $user->id,
            'subscription_id' => $subscriptionId,
            'plan' => $plan,
            'status' => 'active',
        ]);
    }

    private function handleSubscriptionActivated($data)
    {
        $customerId = $data['customer_id'] ?? null;
        $subscriptionId = $data['id'] ?? null;

        if (!$customerId || !$subscriptionId) {
            Log::warning('Missing required fields', [
                'customer_id' => $customerId,
                'subscription_id' => $subscriptionId,
            ]);
            return;
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        
        if (!$user) {
            Log::warning('Subscription for unknown customer, checking subscriptions table', [
                'customer_id' => $customerId
            ]);
            
            $subscription = Subscription::where('paddle_customer_id', $customerId)->first();
            if ($subscription) {
                $user = $subscription->user;
            }
        }
        
        if (!$user) {
            Log::error('Cannot find user for subscription', [
                'customer_id' => $customerId,
                'subscription_id' => $subscriptionId,
            ]);
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
            'plan' => $plan,
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

            // Check if customer already exists
            $error = $response->json('error');
            if ($error && ($error['code'] ?? '') === 'customer_already_exists') {
                // Extract existing customer ID from error message
                $detail = $error['detail'] ?? '';
                if (preg_match('/customer of id (ctm_[a-z0-9]+)/i', $detail, $matches)) {
                    $existingCustomerId = $matches[1];
                    
                    Log::info('Customer already exists in Paddle, using existing ID', [
                        'user_id' => $user->id,
                        'customer_id' => $existingCustomerId,
                    ]);
                    
                    // Save to user record
                    $user->update(['paddle_customer_id' => $existingCustomerId]);
                    
                    return $existingCustomerId;
                }
                
                // If we can't extract ID, search for customer by email
                Log::info('Searching for existing customer by email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                
                $searchResponse = Http::withToken(env('PADDLE_API_KEY'))
                    ->withHeaders(['Paddle-Version' => '1'])
                    ->get($this->api() . '/customers', [
                        'email' => $user->email,
                    ]);
                
                if ($searchResponse->successful()) {
                    $customers = $searchResponse->json('data', []);
                    if (!empty($customers)) {
                        $customerId = $customers[0]['id'];
                        $user->update(['paddle_customer_id' => $customerId]);
                        
                        Log::info('Found existing customer by email', [
                            'user_id' => $user->id,
                            'customer_id' => $customerId,
                        ]);
                        
                        return $customerId;
                    }
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
            Log::warning('Missing signature or secret', [
                'has_signature' => !empty($signature),
                'has_secret' => !empty($secret),
            ]);
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
            Log::warning('Incomplete signature components', [
                'has_ts' => !empty($ts),
                'has_h1' => !empty($h1),
            ]);
            return false;
        }

        // Check timestamp (not older than 5 minutes)
        if (abs(time() - (int)$ts) > 300) {
            Log::warning('Signature timestamp too old', [
                'timestamp' => $ts,
                'age_seconds' => abs(time() - (int)$ts),
            ]);
            return false;
        }

        $payload = $request->getContent();
        $signedPayload = $ts . ':' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        $isValid = hash_equals($expected, $h1);
        
        if (!$isValid) {
            Log::warning('Signature mismatch', [
                'expected_prefix' => substr($expected, 0, 10),
                'received_prefix' => substr($h1, 0, 10),
            ]);
        }

        return $isValid;
    }

    private function detectPlanFromPrice(string $priceId): string
    {
        if ($priceId === env('PADDLE_MONTHLY_PRICE_ID')) {
            return 'monthly';
        }
        if ($priceId === env('PADDLE_ANNUAL_PRICE_ID')) {
            return 'annual';
        }
        
        Log::warning('Unknown price ID', ['price_id' => $priceId]);
        return 'unknown';
    }
}