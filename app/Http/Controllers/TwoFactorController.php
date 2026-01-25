<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show two factor authentication settings page
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile.two-factor', [
            'user' => $user,
            'twoFactorEnabled' => $user->hasTwoFactorEnabled()
        ]);
    }

    /**
     * Show the QR code for enabling 2FA
     */
    public function enable()
    {
        $user = Auth::user();

        // Generate secret if not exists
        $secret = $this->twoFactorService->generateSecretKey();

        // Store temporary secret in session
        session(['two_factor_temp_secret' => $secret]);

        // Generate QR code
        $qrCodeSvg = $this->twoFactorService->getQRCodeSvg($user, $secret);

        return view('profile.two-factor-enable', [
            'secret' => $secret,
            'qrCodeSvg' => $qrCodeSvg
        ]);
    }

    /**
     * Confirm and enable 2FA
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $secret = session('two_factor_temp_secret');

        if (!$secret) {
            return back()->with('error', 'Secret key tidak ditemukan. Silakan coba lagi.');
        }

        // Verify the code
        if (!$this->twoFactorService->enableTwoFactor($user, $secret, $request->code)) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP tidak valid. Silakan coba lagi.'
            ]);
        }

        // Clear temporary secret from session
        session()->forget('two_factor_temp_secret');

        // Get recovery codes
        $recoveryCodes = $this->twoFactorService->getRecoveryCodes($user);

        return redirect()->route('two-factor.recovery-codes')->with([
            'status' => '2FA berhasil diaktifkan!',
            'recovery_codes' => $recoveryCodes
        ]);
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.index');
        }

        $recoveryCodes = $this->twoFactorService->getRecoveryCodes($user);

        return view('profile.two-factor-recovery-codes', [
            'recoveryCodes' => $recoveryCodes
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'password' => 'Password tidak valid.'
            ]);
        }

        $recoveryCodes = $this->twoFactorService->regenerateRecoveryCodes($user);

        return back()->with([
            'status' => 'Recovery codes berhasil di-regenerate!',
            'recovery_codes' => $recoveryCodes
        ]);
    }

    /**
     * Disable two factor authentication
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'code' => 'required|string'
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'password' => 'Password tidak valid.'
            ]);
        }

        // Verify OTP code or recovery code
        $isValidOtp = $this->twoFactorService->verifyCode($user->two_factor_secret, $request->code);
        $isValidRecovery = $this->twoFactorService->verifyRecoveryCode($user, $request->code);

        if (!$isValidOtp && !$isValidRecovery) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP atau Recovery Code tidak valid.'
            ]);
        }

        // Disable 2FA
        $this->twoFactorService->disableTwoFactor($user);

        return redirect()->route('two-factor.index')->with('status', '2FA berhasil dinonaktifkan!');
    }

    /**
     * Show two factor challenge page (during login)
     */
    public function showChallenge()
    {
        if (!session('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify two factor code during login
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $userId = session('two_factor_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if it's a recovery code (10 characters) or OTP (6 digits)
        $code = $request->code;
        $isValid = false;

        if (strlen($code) === 6 && is_numeric($code)) {
            // Verify OTP
            $isValid = $this->twoFactorService->verifyCode($user->two_factor_secret, $code);
        } elseif (strlen($code) === 10) {
            // Verify recovery code
            $isValid = $this->twoFactorService->verifyRecoveryCode($user, $code);
        }

        if (!$isValid) {
            // Increment failed attempts
            $attempts = session('two_factor_attempts', 0) + 1;
            session(['two_factor_attempts' => $attempts]);

            if ($attempts >= 3) {
                // Clear session and redirect to login
                session()->forget(['two_factor_user_id', 'two_factor_attempts']);

                return redirect()->route('login')->with('error', 'Terlalu banyak percobaan gagal. Silakan login kembali.');
            }

            throw ValidationException::withMessages([
                'code' => 'Kode tidak valid. Sisa percobaan: ' . (3 - $attempts)
            ]);
        }

        // Login successful
        Auth::loginUsingId($userId);

        // Mark as verified in this session
        session(['two_factor_verified' => true]);
        session()->forget(['two_factor_user_id', 'two_factor_attempts']);

        return redirect('/dashboard');
    }
}
