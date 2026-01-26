<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for password change routes and logout
        if ($request->routeIs('password.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Check if user must change password
            if ($user->must_change_password) {
                return redirect()->route('password.change')
                    ->with('warning', 'Anda harus mengganti password terlebih dahulu.');
            }

            // Check if password is older than 60 days
            if ($user->password_changed_at) {
                $daysSinceChange = now()->diffInDays($user->password_changed_at);

                if ($daysSinceChange >= 60) {
                    return redirect()->route('password.change')
                        ->with('warning', 'Password Anda sudah kadaluarsa. Silakan ganti password Anda.');
                }

                // Show warning if password will expire in 7 days or less
                if ($daysSinceChange >= 53 && $daysSinceChange < 60) {
                    $daysLeft = 60 - $daysSinceChange;
                    session()->flash('password_warning', "Password Anda akan kadaluarsa dalam {$daysLeft} hari. Segera ganti password Anda.");
                }
            } else {
                // No password_changed_at means this is old data, force password change
                return redirect()->route('password.change')
                    ->with('warning', 'Untuk keamanan, silakan set password baru Anda.');
            }
        }

        return $next($request);
    }
}
