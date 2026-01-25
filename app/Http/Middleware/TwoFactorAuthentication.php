<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Skip if user is not authenticated
        if (!$user) {
            return $next($request);
        }

        // Skip if accessing 2FA related routes
        if ($request->routeIs('two-factor.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if user has 2FA enabled and not yet verified in this session
        if ($user->hasTwoFactorEnabled() && !session('two_factor_verified')) {
            // Store user ID in session for verification
            session(['two_factor_user_id' => $user->id]);

            // Logout the user temporarily
            auth()->logout();

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
