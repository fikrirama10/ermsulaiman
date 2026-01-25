<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticateUsers;
use App\Models\User;
use Auth;
use Hash;
use Session;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username','password');

        if(Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            // Check if user has two factor authentication enabled
            if ($user->hasTwoFactorEnabled()) {
                // Store user ID in session for 2FA verification
                session(['two_factor_user_id' => $user->id]);

                // Logout temporarily until 2FA is verified
                Auth::logout();

                // Redirect to 2FA challenge page
                return redirect()->route('two-factor.challenge');
            }

            // User doesn't have 2FA enabled, continue with normal login
            $request->session()->regenerate();
            session(['two_factor_verified' => true]); // Mark as verified (no 2FA needed)

            return redirect('/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
