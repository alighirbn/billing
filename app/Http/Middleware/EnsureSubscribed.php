<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSubscribed
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user?->subscription || $user->subscription->status !== 'active') {
            return redirect()->route('pricing')
                ->with('error', 'Please subscribe to continue.');
        }

        return $next($request);
    }
}
