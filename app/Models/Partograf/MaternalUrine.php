<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaternalUrine extends Model
{
    use HasFactory;

    protected $table = 'maternal_urine';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'observation_time' => 'datetime',
    ];

    /**
     * Relationship: Urine record belongs to a labor record
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
     * Check if protein is positive
     */
    public function hasProtein()
    {
        return !in_array($this->protein, ['negative', null]);
    }

    /**
     * Check if acetone/ketone is positive
     */
    public function hasAcetone()
    {
        return !in_array($this->acetone, ['negative', null]);
    }

    /**
     * Get urine analysis summary
     */
    public function getSummary()
    {
        $summary = [];

        if ($this->hasProtein()) {
            $summary[] = "Protein: {$this->protein}";
        }

        if ($this->hasAcetone()) {
            $summary[] = "Keton: {$this->acetone}";
        }

        if (empty($summary)) {
            return 'Normal';
        }

        return implode(', ', $summary);
    }

    /**
     * Check if urine output is adequate (>= 30 ml/hour or >= 120ml per 4 hours)
     */
    public function isOutputAdequate()
    {
        // Minimal 30ml/jam = 120ml per 4 jam
        return $this->volume_ml >= 120;
    }
}
