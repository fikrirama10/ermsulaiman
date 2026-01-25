<?php

namespace App\Models\Partograf;

use App\Models\Dokter;
use App\Models\Rawat;
use App\Models\Pasien\Pasien;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LaborRecord extends Model
{
    use HasFactory;

    protected $table = 'labor_records';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    protected $casts = [
        'admission_date' => 'datetime',
        'membrane_rupture_time' => 'datetime',
        'labor_start_time' => 'datetime',
        'labor_end_time' => 'datetime',
        'initial_risk_assessment' => 'array',
    ];

    /**
     * Relationship: Labor record belongs to a visit (rawat)
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Rawat::class, 'visit_id', 'id');
    }

    /**
     * Relationship: Labor record belongs to a patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'patient_id', 'id');
    }

    /**
     * Relationship: Labor record has many progress observations
     */
    public function progresses(): HasMany
    {
        return $this->hasMany(LaborProgress::class, 'labor_record_id', 'id')
            ->orderBy('observation_time', 'asc');
    }

    /**
     * Relationship: Labor record has many uterine contractions
     */
    public function contractions(): HasMany
    {
        return $this->hasMany(UterineContraction::class, 'labor_record_id', 'id')
            ->orderBy('observation_time', 'asc');
    }

    /**
     * Relationship: Labor record has many fetal monitoring records
     */
    public function fetalMonitorings(): HasMany
    {
        return $this->hasMany(FetalMonitoring::class, 'labor_record_id', 'id')
            ->orderBy('observation_time', 'asc');
    }

    /**
     * Relationship: Labor record has many maternal vital signs
     */
    public function maternalVitals(): HasMany
    {
        return $this->hasMany(MaternalVital::class, 'labor_record_id', 'id')
            ->orderBy('observation_time', 'asc');
    }

    /**
     * Relationship: Labor record has many maternal urine records
     */
    public function maternalUrines(): HasMany
    {
        return $this->hasMany(MaternalUrine::class, 'labor_record_id', 'id')
            ->orderBy('observation_time', 'asc');
    }

    /**
     * Relationship: Labor record has many medications
     */
    public function medications(): HasMany
    {
        return $this->hasMany(LaborMedication::class, 'labor_record_id', 'id')
            ->orderBy('administration_time', 'asc');
    }

    /**
     * Relationship: Labor record has one partograph line
     */
    public function partographLine(): HasOne
    {
        return $this->hasOne(PartographLine::class, 'labor_record_id', 'id');
    }

    /**
     * Relationship: Midwife who handled the labor
     */
    public function midwife(): BelongsTo
    {
        return $this->belongsTo(User::class, 'midwife_id', 'id');
    }

    /**
     * Relationship: Doctor who supervised the labor
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'doctor_id', 'id');
    }

    /**
     * Relationship: User who created the record
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Scope: Filter ongoing labors
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope: Filter completed labors
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Filter today's labors
     */
    public function scopeToday($query)
    {
        return $query->whereDate('admission_date', today());
    }

    /**
     * Accessor: Get progress percentage based on cervical dilatation
     */
    public function getProgressPercentageAttribute()
    {
        $latestProgress = $this->progresses()->latest('observation_time')->first();

        if (!$latestProgress) {
            return 0;
        }

        return ($latestProgress->cervical_dilatation / 10) * 100;
    }

    /**
     * Accessor: Check if labor is in alert zone
     */
    public function getIsAlertAttribute()
    {
        if (!$this->partographLine) {
            return false;
        }

        $latestProgress = $this->progresses()->latest('observation_time')->first();

        if (!$latestProgress) {
            return false;
        }

        // Calculate expected dilatation on alert line
        $hoursSinceAlertStart = $latestProgress->observation_time->diffInHours($this->partographLine->alert_line_start_time);
        $expectedDilatation = 3 + ($hoursSinceAlertStart * 1); // 1 cm per hour from 3cm

        return $latestProgress->cervical_dilatation < $expectedDilatation;
    }

    /**
     * Accessor: Check if labor is in action zone
     */
    public function getIsActionAttribute()
    {
        if (!$this->partographLine) {
            return false;
        }

        $latestProgress = $this->progresses()->latest('observation_time')->first();

        if (!$latestProgress) {
            return false;
        }

        // Calculate expected dilatation on action line
        $hoursSinceActionStart = $latestProgress->observation_time->diffInHours($this->partographLine->action_line_start_time);
        $expectedDilatation = 3 + ($hoursSinceActionStart * 1); // 1 cm per hour from 3cm

        return $latestProgress->cervical_dilatation < $expectedDilatation;
    }

    /**
     * Get latest fetal heart rate
     */
    public function getLatestFetalHeartRate()
    {
        $latest = $this->fetalMonitorings()->latest('observation_time')->first();
        return $latest ? $latest->fetal_heart_rate : null;
    }

    /**
     * Check if fetal heart rate is abnormal
     */
    public function hasAbnormalFetalHeartRate()
    {
        $rate = $this->getLatestFetalHeartRate();

        if (!$rate) {
            return false;
        }

        return $rate < 120 || $rate > 160;
    }

    /**
     * Get labor duration in hours
     */
    public function getLaborDurationAttribute()
    {
        $endTime = $this->labor_end_time ?? now();
        return $this->labor_start_time->diffInHours($endTime);
    }

    /**
     * Check if a specific risk factor is present
     */
    public function hasRiskFactor($key)
    {
        return $this->initial_risk_assessment[$key] ?? false;
    }

    /**
     * Get list of active risk factors
     */
    public function getActiveRiskFactors()
    {
        if (!$this->initial_risk_assessment) {
            return [];
        }

        return collect($this->initial_risk_assessment)
            ->filter(fn($value) => $value === true)
            ->keys()
            ->toArray();
    }

    /**
     * Get active risk factor count
     */
    public function getRiskFactorCountAttribute()
    {
        return count($this->getActiveRiskFactors());
    }

    /**
     * Computed risk level based on number of active factors
     */
    public function getRiskLevelAttribute()
    {
        $count = $this->risk_factor_count;

        if ($count >= 6) return 'high';
        if ($count >= 3) return 'medium';
        return 'low';
    }

    /**
     * Get risk level color for UI badges
     */
    public function getRiskColorAttribute()
    {
        return match ($this->risk_level) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get risk level label in Indonesian
     */
    public function getRiskLabelAttribute()
    {
        return match ($this->risk_level) {
            'high' => 'Risiko Tinggi',
            'medium' => 'Risiko Sedang',
            'low' => 'Risiko Rendah',
            default => 'Tidak Dinilai',
        };
    }
}
