<?php

namespace App\Models\Partograf;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaborProgress extends Model
{
    use HasFactory;

    protected $table = 'labor_progress';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'observation_time' => 'datetime',
        'cervical_dilatation' => 'decimal:1',
    ];

    protected $attributes = [
        'created_at' => null,
    ];

    /**
     * Relationship: Progress belongs to a labor record
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
     * Check if cervical dilatation is complete
     */
    public function isFullyDilated()
    {
        return $this->cervical_dilatation >= 10;
    }

    /**
     * Get fetal head descent as numeric value
     */
    public function getDescentNumeric()
    {
        $mapping = [
            '5/5' => 0,
            '4/5' => 1,
            '3/5' => 2,
            '2/5' => 3,
            '1/5' => 4,
            '0/5' => 5,
        ];

        return $mapping[$this->fetal_head_descent] ?? 0;
    }
}
