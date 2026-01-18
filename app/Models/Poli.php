<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    use HasFactory;
    protected $table = 'poli';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function rawat(): BelongsTo
    {
        return $this->belongsTo(Rawat::class);
    }

    // Relationship with dokter jadwal
    public function dokterJadwal(): HasMany
    {
        return $this->hasMany(DokterJadwal::class, 'idpoli', 'id');
    }

    // Relationship with dokter kuota
    public function dokterKuota(): HasMany
    {
        return $this->hasMany(DokterKuota::class, 'idpoli', 'id');
    }
}
