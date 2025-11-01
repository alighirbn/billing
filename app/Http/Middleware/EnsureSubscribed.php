<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $subscription = $user->subscription;

        // Check if user has an active subscription
        if (!$subscription || $subscription->status !== 'active') {
            return redirect()->route('pricing')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
}