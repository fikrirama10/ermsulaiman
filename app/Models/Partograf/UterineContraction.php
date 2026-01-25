<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UterineContraction extends Model
{
    use HasFactory;

    protected $table = 'uterine_contractions';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'observation_time' => 'datetime',
    ];

    /**
     * Relationship: Contraction belongs to a labor record
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
     * Get contraction strength description
     */
    public function getStrengthDescription()
    {
        $descriptions = [
            'weak' => 'Lemah',
            'moderate' => 'Sedang',
            'strong' => 'Kuat',
        ];

        return $descriptions[$this->intensity] ?? '-';
    }

    /**
     * Check if contractions are adequate (at least 3 in 10 min, moderate to strong)
     */
    public function isAdequate()
    {
        return $this->contractions_per_10min >= 3 &&
            in_array($this->intensity, ['moderate', 'strong']);
    }
}
