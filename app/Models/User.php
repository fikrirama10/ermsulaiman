<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $guard = 'web';
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function username()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function detail()
    {
        return $this->belongsTo(UserDetail::class, 'kode_user', 'kode_user');
    }

    public function privilages()
    {
        return $this->belongsTo(UserPrivilages::class, 'idpriv');
    }

    /**
     * Check if the user's password has expired (>60 days).
     */
    public function passwordExpired(): bool
    {
        if (!$this->password_changed_at) {
            return true; // Consider it expired if never set
        }

        return now()->diffInDays($this->password_changed_at) >= 60;
    }

    /**
     * Get the number of days until password expires.
     */
    public function daysUntilPasswordExpiry(): int
    {
        if (!$this->password_changed_at) {
            return 0;
        }

        $daysSinceChange = now()->diffInDays($this->password_changed_at);
        return max(0, 60 - $daysSinceChange);
    }

    /**
     * Check if password will expire soon (within 7 days).
     */
    public function passwordExpiringSoon(): bool
    {
        return $this->daysUntilPasswordExpiry() > 0 && $this->daysUntilPasswordExpiry() <= 7;
    }
}
