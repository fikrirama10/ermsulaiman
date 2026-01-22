<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'event',
        'properties',
        'batch_uuid',
        'user_id',
        'user_name',
        'user_role',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'no_rm',
        'idrawat',
        'poli',
        'dokter',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject (polymorphic)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (polymorphic)
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filter by log name
     */
    public function scopeOfLogName($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope: Filter by event
     */
    public function scopeOfEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by patient (no_rm)
     */
    public function scopeByPatient($query, $noRm)
    {
        return $query->where('no_rm', $noRm);
    }

    /**
     * Scope: Filter by rawat
     */
    public function scopeByRawat($query, $idrawat)
    {
        return $query->where('idrawat', $idrawat);
    }

    /**
     * Scope: Today's activities
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: This week's activities
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope: This month's activities
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Get changes (old vs new values)
     */
    public function getChangesAttribute()
    {
        if (!isset($this->properties['attributes']) || !isset($this->properties['old'])) {
            return [];
        }

        $changes = [];
        $attributes = $this->properties['attributes'];
        $old = $this->properties['old'];

        foreach ($attributes as $key => $value) {
            if (!isset($old[$key]) || $old[$key] != $value) {
                $changes[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute()
    {
        $badges = [
            'created' => '<span class="badge badge-success">Created</span>',
            'updated' => '<span class="badge badge-primary">Updated</span>',
            'deleted' => '<span class="badge badge-danger">Deleted</span>',
            'viewed' => '<span class="badge badge-info">Viewed</span>',
        ];

        $badge = $badges[$this->event] ?? '';
        return $badge . ' ' . $this->description;
    }

    /**
     * Get icon for event
     */
    public function getEventIconAttribute()
    {
        $icons = [
            'created' => '<i class="fas fa-plus-circle text-success"></i>',
            'updated' => '<i class="fas fa-edit text-primary"></i>',
            'deleted' => '<i class="fas fa-trash text-danger"></i>',
            'viewed' => '<i class="fas fa-eye text-info"></i>',
        ];

        return $icons[$this->event] ?? '<i class="fas fa-circle text-secondary"></i>';
    }
}
