<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the password change form.
     */
    public function showChangeForm()
    {
        $user = Auth::user();

        // Calculate days until expiry
        $daysUntilExpiry = null;
        if ($user->password_changed_at) {
            $daysSinceChange = now()->diffInDays($user->password_changed_at);
            $daysUntilExpiry = 60 - $daysSinceChange;
        }

        return view('auth.change-password', compact('user', 'daysUntilExpiry'));
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password_hash)) {
                    $fail('Password lama tidak sesuai.');
                }
            }],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols(),
                function ($attribute, $value, $fail) use ($request, $user) {
                    if (Hash::check($value, $user->password_hash)) {
                        $fail('Password baru tidak boleh sama dengan password lama.');
                    }
                },
            ],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.mixed_case' => 'Password harus mengandung huruf besar dan kecil.',
            'password.symbols' => 'Password harus mengandung simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update password
        $user->password_hash = Hash::make($request->password);
        $user->password = $request->password; // For compatibility with existing system
        $user->password_changed_at = now();
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diubah. Password Anda akan kadaluarsa dalam 60 hari.');
    }
}
