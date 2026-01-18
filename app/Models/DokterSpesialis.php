<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DokterSpesialis extends Model
{
    use HasFactory;
    protected $table = 'dokter_spesialis';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    // Relationship with dokter
    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class, 'idspesialis', 'id');
    }
}
