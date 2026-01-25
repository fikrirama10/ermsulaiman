<?php

namespace App\Traits;

use App\Services\TwoFactorService;

trait TwoFactorAuthenticatable
{
    /**
     * Check if two factor authentication is enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled == true && !empty($this->two_factor_secret);
    }

    /**
     * Get recovery codes as array
     */
    public function recoveryCodes(): array
    {
        return json_decode($this->two_factor_recovery_codes, true) ?? [];
    }

    /**
     * Check if user has recovery codes
     */
    public function hasRecoveryCodes(): bool
    {
        return !empty($this->recoveryCodes());
    }
}
