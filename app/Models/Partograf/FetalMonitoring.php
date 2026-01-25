<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FetalMonitoring extends Model
{
    use HasFactory;

    protected $table = 'fetal_monitoring';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'observation_time' => 'datetime',
    ];

    /**
     * Relationship: Fetal monitoring belongs to a labor record
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
     * Check if fetal heart rate is normal (120-160 bpm)
     */
    public function isFetalHeartRateNormal()
    {
        return $this->fetal_heart_rate >= 120 && $this->fetal_heart_rate <= 160;
    }

    /**
     * Get fetal heart rate status
     */
    public function getFetalHeartRateStatus()
    {
        if ($this->fetal_heart_rate < 120) {
            return 'bradycardia'; // DJJ rendah
        } elseif ($this->fetal_heart_rate > 160) {
            return 'tachycardia'; // DJJ tinggi
        }

        return 'normal';
    }

    /**
     * Get amniotic fluid color description
     */
    public function getAmnioticFluidDescription()
    {
        $descriptions = [
            'intact' => 'Utuh (U)',
            'clear' => 'Jernih (J)',
            'meconium' => 'Mekonium (M)',
            'blood' => 'Darah (D)',
        ];

        return $descriptions[$this->amniotic_fluid_color] ?? '-';
    }

    /**
     * Check if amniotic fluid indicates potential fetal distress
     */
    public function hasAbnormalAmnioticFluid()
    {
        return in_array($this->amniotic_fluid_color, ['meconium', 'blood']);
    }
}
