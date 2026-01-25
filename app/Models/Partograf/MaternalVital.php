<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaternalVital extends Model
{
    use HasFactory;

    protected $table = 'maternal_vitals';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'observation_time' => 'datetime',
        'temperature' => 'decimal:2',
    ];

    /**
     * Relationship: Vital belongs to a labor record
     */
    public function laborRecord(): BelongsTo
    {
        return $this->belongsTo(LaborRecord::class, 'labor_record_id', 'id');
    }

    /**
     * Relationship: User who recorded this observation
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by', 'id');
    }

    /**
     * Get blood pressure as formatted string
     */
    public function getBloodPressureAttribute()
    {
        if (!$this->blood_pressure_systolic || !$this->blood_pressure_diastolic) {
            return '-';
        }

        return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic;
    }

    /**
     * Check if blood pressure is hypertensive (>= 140/90)
     */
    public function isHypertensive()
    {
        return $this->blood_pressure_systolic >= 140 || $this->blood_pressure_diastolic >= 90;
    }

    /**
     * Check if blood pressure is hypotensive (< 90/60)
     */
    public function isHypotensive()
    {
        return $this->blood_pressure_systolic < 90 || $this->blood_pressure_diastolic < 60;
    }

    /**
     * Check if pulse rate is normal (60-100 bpm)
     */
    public function isPulseNormal()
    {
        return $this->pulse_rate >= 60 && $this->pulse_rate <= 100;
    }

    /**
     * Check if temperature is febrile (>= 38°C)
     */
    public function isFebrile()
    {
        return $this->temperature >= 38;
    }

    /**
     * Get vital signs status
     */
    public function getVitalStatus()
    {
        $alerts = [];

        if ($this->isHypertensive()) {
            $alerts[] = 'Hipertensi';
        }

        if ($this->isHypotensive()) {
            $alerts[] = 'Hipotensi';
        }

        if (!$this->isPulseNormal()) {
            $alerts[] = 'Nadi Abnormal';
        }

        if ($this->isFebrile()) {
            $alerts[] = 'Demam';
        }

        return empty($alerts) ? 'Normal' : implode(', ', $alerts);
    }
}
