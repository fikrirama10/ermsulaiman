<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaborMedication extends Model
{
    use HasFactory;

    protected $table = 'labor_medications';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'administration_time' => 'datetime',
    ];

    /**
     * Relationship: Medication belongs to a labor record
     */
    public function laborRecord(): BelongsTo
    {
        return $this->belongsTo(LaborRecord::class, 'labor_record_id', 'id');
    }

    /**
     * Relationship: User who administered the medication
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administered_by', 'id');
    }

    /**
     * Get medication type description
     */
    public function getTypeDescription()
    {
        $descriptions = [
            'drug' => 'Obat',
            'iv_fluid' => 'Cairan IV',
            'oxytocin' => 'Oksitosin',
        ];

        return $descriptions[$this->medication_type] ?? '-';
    }

    /**
     * Get route description
     */
    public function getRouteDescription()
    {
        $descriptions = [
            'oral' => 'Per Oral',
            'iv' => 'Intravena',
            'im' => 'Intramuskular',
            'sc' => 'Subkutan',
        ];

        return $descriptions[$this->route] ?? '-';
    }

    /**
     * Get full medication summary
     */
    public function getSummary()
    {
        $summary = $this->medication_name;

        if ($this->dosage) {
            $summary .= " ({$this->dosage})";
        }

        $summary .= " - " . $this->getRouteDescription();

        return $summary;
    }
}
