<?php

namespace App\Models\Partograf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartographLine extends Model
{
    use HasFactory;

    protected $table = 'partograph_lines';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'alert_line_start_time' => 'datetime',
        'action_line_start_time' => 'datetime',
    ];

    /**
     * Relationship: Partograph line belongs to a labor record
     */
    public function laborRecord(): BelongsTo
    {
        return $this->belongsTo(LaborRecord::class, 'labor_record_id', 'id');
    }

    /**
     * Calculate expected dilatation at given time on alert line
     * Alert line: starts at 3cm, progresses 1cm/hour
     */
    public function getExpectedDilatationOnAlertLine($observationTime)
    {
        $hoursSinceStart = $observationTime->diffInHours($this->alert_line_start_time);

        // Start from 3cm, add 1cm per hour
        $expectedDilatation = 3 + $hoursSinceStart;

        // Cap at 10cm (fully dilated)
        return min($expectedDilatation, 10);
    }

    /**
     * Calculate expected dilatation at given time on action line
     * Action line: 4 hours after alert line
     */
    public function getExpectedDilatationOnActionLine($observationTime)
    {
        $hoursSinceStart = $observationTime->diffInHours($this->action_line_start_time);

        // Start from 3cm, add 1cm per hour
        $expectedDilatation = 3 + $hoursSinceStart;

        // Cap at 10cm (fully dilated)
        return min($expectedDilatation, 10);
    }

    /**
     * Check if given dilatation is crossing alert line
     */
    public function isCrossingAlertLine($dilatation, $observationTime)
    {
        $expectedDilatation = $this->getExpectedDilatationOnAlertLine($observationTime);
        return $dilatation < $expectedDilatation;
    }

    /**
     * Check if given dilatation is crossing action line
     */
    public function isCrossingActionLine($dilatation, $observationTime)
    {
        $expectedDilatation = $this->getExpectedDilatationOnActionLine($observationTime);
        return $dilatation < $expectedDilatation;
    }
}
