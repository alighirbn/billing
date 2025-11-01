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
            $customerId = $this->ensurePaddleCustomer($user);

            $payload = [
                'customer_id' => $customerId,
                'items' => [[
                    'price_id' => $plans[$plan],
                    'quantity' => 1,
                ]],
                'success_url' => route('dashboard'),
                'cancel_url'  => route('pricing'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan'    => $plan,
                ],
            ];

            $response = Http::withToken(env('PADDLE_API_KEY'))
                ->withHeaders(['Paddle-Version' => '1'])
                ->post($this->api() . '/transactions', $payload);

            Log::info('Paddle /transactions result', [
                'http_status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->failed()) {
                $detail = $response->json('error.detail') ?? 'Request failed';
                return back()->withErrors('Paddle Error: ' . $detail);
            }

            $data = $response->json('data') ?? [];

            // ✅ Get Paddle V2 checkout URL correctly
            $checkoutUrl = $data['checkout']['url'] ?? null;

            // ✅ Backward compatibility with v1 (optional fallback)
            if (!$checkoutUrl && !empty($data['checkout_url'])) {
                $checkoutUrl = $data['checkout_url'];
            }

            if ($checkoutUrl) {
                Subscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan' => $plan,
                        'status' => 'pending',
                        'paddle_customer_id' => $customerId,
                        'meta' => ['transaction_id' => $data['id'] ?? null],
                    ]
                );

                return view('checkout', [
                    'checkout_url' => $checkoutUrl,
                ]);
            }

            if (!empty($data['status']) && in_array($data['status'], ['completed', 'active'])) {
                Subscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan' => $plan,
                        'status' => 'active',
                        'paddle_customer_id' => $customerId,
                        'paddle_subscription_id' => $data['subscription_id'] ?? null,
                    ]
                );

                return redirect()->route('dashboard')
                    ->with('success', 'Subscription active.');
            }

            if (($data['status'] ?? null) === 'requires_payment_method' && !empty($data['payment_method_url'])) {
                return redirect()->away($data['payment_method_url']);
            }

            return back()->withErrors('Unexpected response from Paddle. Please try again.');
        } catch (\Exception $e) {
            Log::error('Paddle checkout exception', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors('An error occurred. Please try again.');
        }
    }


    public function webhook(Request $request)
    {
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid Paddle webhook signature', ['ip' => $request->ip()]);
            return response('Invalid signature', 403);
        }

        $eventType = $request->input('event_type');
        $data = $request->input('data', []);

        $paddleSubId = $data['id'] ?? null;              // subscription ID for sub events
        $customerId  = $data['customer_id'] ?? null;

        if (!$customerId) {
            return response('Missing customer_id', 400);
        }

        $user = User::where('paddle_customer_id', $customerId)->first();
        if (!$user) {
            Log::warning('Webhook user not found', ['customer_id' => $customerId]);
            return response('User not found', 404);
        }

        try {
            switch ($eventType) {
                case 'subscription.activated':
                case 'subscription.updated':
                    $priceId = $data['items'][0]['price_id'] ?? '';
                    $plan = $this->detectPlanFromPrice($priceId);

                    Subscription::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'paddle_subscription_id' => $paddleSubId,
                            'paddle_customer_id' => $customerId,
                            'plan' => $plan,
                            'status' => 'active',
                            'current_period_start' => $data['current_period_start'] ?? now(),
                            'current_period_end'   => $data['current_period_end'] ?? now()->addMonth(),
                            'meta' => $data,
                        ]
                    );
                    break;

                case 'subscription.canceled':
                    Subscription::where('paddle_subscription_id', $paddleSubId)
                        ->update([
                            'status' => 'canceled',
                            'canceled_at' => now(),
                        ]);
                    break;

                case 'subscription.past_due':
                    Subscription::where('paddle_subscription_id', $paddleSubId)
                        ->update(['status' => 'past_due']);
                    break;

                default:
                    Log::info('Unhandled Paddle webhook', ['event' => $eventType]);
            }
        } catch (Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return response('Error', 500);
        }

        return response('OK', 200);
    }

    private function ensurePaddleCustomer(User $user): string
    {
        if ($user->paddle_customer_id) {
            return $user->paddle_customer_id;
        }

        $resp = Http::withToken(env('PADDLE_API_KEY'))
            ->withHeaders(['Paddle-Version' => '1'])
            ->post($this->api() . '/customers', [
                'email' => $user->email,
                'name'  => $user->name ?? $user->email,
            ]);

        Log::info('Paddle /customers result', [
            'http_status' => $resp->status(),
            'body' => $resp->json(),
        ]);

        if ($resp->failed()) {
            $detail = $resp->json('error.detail') ?? 'Failed to create customer';
            throw new Exception($detail);
        }

        $customerId = $resp->json('data.id');
        $user->update(['paddle_customer_id' => $customerId]);

        return $customerId;
    }

    // Paddle v2 HMAC header: "ts=...,h1=..."
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
            if (str_starts_with($el, 'ts=')) $ts = substr($el, 3);
            if (str_starts_with($el, 'h1=')) $h1 = substr($el, 3);
        }

        if (!$ts || !$h1) {
            return false;
        }

        // Reject stale notifications
        if (abs(time() - (int)$ts) > 300) {
            return false;
        }

        $payload = $request->getContent();
        $expected = hash_hmac('sha256', $ts . $payload, $secret);

        return hash_equals($expected, $h1);
    }

    private function detectPlanFromPrice(string $priceId): string
    {
        if ($priceId === env('PADDLE_MONTHLY_PRICE_ID')) return 'monthly';
        if ($priceId === env('PADDLE_ANNUAL_PRICE_ID'))  return 'annual';
        return 'unknown';
    }
}
