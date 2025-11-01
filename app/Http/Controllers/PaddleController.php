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

        $plans = [
            'monthly' => env('PADDLE_MONTHLY_PRICE_ID'),
            'annual'  => env('PADDLE_ANNUAL_PRICE_ID'),
        ];

        if (empty($plans[$plan])) {
            return back()->withErrors('Invalid plan selected.');
        }

        $user = $request->user();

        try {
            // Ensure Paddle customer exists
            $customerId = $this->ensurePaddleCustomer($user);

            $payload = [
                'items' => [[
                    'price_id' => $plans[$plan],
                    'quantity' => 1,
                ]],
                'customer_id' => $customerId,
                'custom_data' => [
                    'user_id' => (string) $user->id,
                    'plan'    => $plan,
                ],
            ];

            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post($this->api() . '/transactions', $payload);

            Log::info('Paddle /transactions request', [
                'payload' => $payload,
                'http_status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->failed()) {
                $error = $response->json();
                $detail = $error['error']['detail'] ?? $error['error']['message'] ?? 'Request failed';
                Log::error('Paddle transaction failed', ['error' => $error]);
                return back()->withErrors('Paddle Error: ' . $detail);
            }

            $data = $response->json('data') ?? [];

            // Create or update subscription record
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

            // Get checkout URL from Paddle V2 response
            $checkoutUrl = $data['checkout']['url'] ?? null;

            if (!$checkoutUrl) {
                Log::error('No checkout URL in Paddle response', ['data' => $data]);
                return back()->withErrors('Unable to create checkout session. Please try again.');
            }

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
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid Paddle webhook signature', ['ip' => $request->ip()]);
            return response('Invalid signature', 403);
        }

        $eventType = $request->input('event_type');
        $data = $request->input('data', []);

        Log::info('Processing Paddle webhook', [
            'event_type' => $eventType,
            'data' => $data,
        ]);

        try {
            switch ($eventType) {
                case 'transaction.completed':
                    $this->handleTransactionCompleted($data);
                    break;

                case 'transaction.paid':
                    $this->handleTransactionPaid($data);
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

                case 'subscription.paused':
                    $this->handleSubscriptionPaused($data);
                    break;

                default:
                    Log::info('Unhandled Paddle webhook event', ['event' => $eventType]);
            }
        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response('Error processing webhook', 500);
        }

        return response('OK', 200);
    }

    private function handleTransactionCompleted($data)
    {
        $customerId = $data['customer_id'] ?? null;
        if (!$customerId) {
            Log::warning('Transaction completed without customer_id', ['data' => $data]);
            return;
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        if (!$user) {
            Log::warning('Transaction completed for unknown customer', ['customer_id' => $customerId]);
            return;
        }

        // Get subscription ID if this is a subscription transaction
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

            Log::info('Subscription activated from transaction', [
                'user_id' => $user->id,
                'subscription_id' => $subscriptionId,
            ]);
        }
    }

    private function handleTransactionPaid($data)
    {
        // Similar to transaction.completed
        $this->handleTransactionCompleted($data);
    }

    private function handleSubscriptionActivated($data)
    {
        $customerId = $data['customer_id'] ?? null;
        $subscriptionId = $data['id'] ?? null;

        if (!$customerId || !$subscriptionId) {
            Log::warning('Subscription event missing required fields', ['data' => $data]);
            return;
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        if (!$user) {
            Log::warning('Subscription activated for unknown customer', ['customer_id' => $customerId]);
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

        Log::info('Subscription activated/updated', [
            'user_id' => $user->id,
            'subscription_id' => $subscriptionId,
            'plan' => $plan,
        ]);
    }

    private function handleSubscriptionCanceled($data)
    {
        $subscriptionId = $data['id'] ?? null;
        if (!$subscriptionId) {
            return;
        }

        Subscription::where('paddle_subscription_id', $subscriptionId)
            ->update([
                'status' => 'canceled',
                'canceled_at' => now(),
                'meta' => $data,
            ]);

        Log::info('Subscription canceled', ['subscription_id' => $subscriptionId]);
    }

    private function handleSubscriptionPastDue($data)
    {
        $subscriptionId = $data['id'] ?? null;
        if (!$subscriptionId) {
            return;
        }

        Subscription::where('paddle_subscription_id', $subscriptionId)
            ->update([
                'status' => 'past_due',
                'meta' => $data,
            ]);

        Log::info('Subscription past due', ['subscription_id' => $subscriptionId]);
    }

    private function handleSubscriptionPaused($data)
    {
        $subscriptionId = $data['id'] ?? null;
        if (!$subscriptionId) {
            return;
        }

        Subscription::where('paddle_subscription_id', $subscriptionId)
            ->update([
                'status' => 'paused',
                'meta' => $data,
            ]);

        Log::info('Subscription paused', ['subscription_id' => $subscriptionId]);
    }

    private function ensurePaddleCustomer(User $user): string
    {
        // Return existing customer ID if available
        if ($user->paddle_customer_id) {
            Log::info('Using existing Paddle customer', [
                'user_id' => $user->id,
                'customer_id' => $user->paddle_customer_id,
            ]);
            return $user->paddle_customer_id;
        }

        // Create new Paddle customer
        $payload = [
            'email' => $user->email,
            'name' => $user->name ?? $user->email,
        ];

        $response = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->post($this->api() . '/customers', $payload);

        Log::info('Paddle /customers request', [
            'payload' => $payload,
            'http_status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->failed()) {
            $error = $response->json();
            $detail = $error['error']['detail'] ?? $error['error']['message'] ?? 'Failed to create customer';
            Log::error('Failed to create Paddle customer', ['error' => $error]);
            throw new Exception('Failed to create Paddle customer: ' . $detail);
        }

        $customerId = $response->json('data.id');
        if (!$customerId) {
            throw new Exception('No customer ID returned from Paddle');
        }

        // Save customer ID to user
        $user->update(['paddle_customer_id' => $customerId]);

        Log::info('Created Paddle customer', [
            'user_id' => $user->id,
            'customer_id' => $customerId,
        ]);

        return $customerId;
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('Paddle-Signature');
        $secret = env('PADDLE_WEBHOOK_SECRET');

        if (!$signature || !$secret) {
            Log::warning('Missing webhook signature or secret');
            return false;
        }

        $ts = null;
        $h1 = null;
        
        // Parse signature header: "ts=1234567890;h1=abc123..."
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
            Log::warning('Invalid webhook signature format', ['signature' => $signature]);
            return false;
        }

        // Reject stale notifications (older than 5 minutes)
        if (abs(time() - (int)$ts) > 300) {
            Log::warning('Webhook signature timestamp too old', ['ts' => $ts]);
            return false;
        }

        // Verify signature
        $payload = $request->getContent();
        $signedPayload = $ts . ':' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        $isValid = hash_equals($expected, $h1);

        if (!$isValid) {
            Log::warning('Webhook signature mismatch', [
                'expected' => $expected,
                'received' => $h1,
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