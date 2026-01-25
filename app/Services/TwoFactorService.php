<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for the user
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR Code SVG for Google Authenticator
     */
    public function getQRCodeSvg(User $user, string $secret): string
    {
        $appName = config('app.name', 'ERM Sulaiman');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            $appName,
            $user->username,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        return $writer->writeString($qrCodeUrl);
    }

    /**
     * Verify the OTP code
     */
    public function verifyCode(string $secret, string $code): bool
    {
        try {
            $decryptedSecret = Crypt::decryptString($secret);
            return $this->google2fa->verifyKey($decryptedSecret, $code);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Enable two factor authentication for user
     */
    public function enableTwoFactor(User $user, string $secret, string $code): bool
    {
        // Verify the code first
        if (!$this->google2fa->verifyKey($secret, $code)) {
            return false;
        }

        // Encrypt and save the secret
        $user->two_factor_secret = Crypt::encryptString($secret);
        $user->two_factor_recovery_codes = json_encode($this->generateRecoveryCodes());
        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        return true;
    }

    /**
     * Disable two factor authentication
     */
    public function disableTwoFactor(User $user): bool
    {
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_enabled = false;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return true;
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes(): array
    {
        $recoveryCodes = [];

        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        }

        return $recoveryCodes;
    }

    /**
     * Get recovery codes for user
     */
    public function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode($user->two_factor_recovery_codes, true);
    }

    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $recoveryCodes = $this->getRecoveryCodes($user);

        if (!in_array(strtoupper($code), $recoveryCodes)) {
            return false;
        }

        // Remove used recovery code
        $remainingCodes = array_filter($recoveryCodes, function($recoveryCode) use ($code) {
            return $recoveryCode !== strtoupper($code);
        });

        $user->two_factor_recovery_codes = json_encode(array_values($remainingCodes));
        $user->save();

        return true;
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        $newCodes = $this->generateRecoveryCodes();
        $user->two_factor_recovery_codes = json_encode($newCodes);
        $user->save();

        return $newCodes;
    }

    /**
     * Check if user has two factor enabled
     */
    public function isEnabled(User $user): bool
    {
        return $user->two_factor_enabled == true && !empty($user->two_factor_secret);
    }

    /**
     * Get the current OTP for testing (development only)
     */
    public function getCurrentOtp(string $encryptedSecret): string
    {
        $secret = Crypt::decryptString($encryptedSecret);
        return $this->google2fa->getCurrentOtp($secret);
    }
}
