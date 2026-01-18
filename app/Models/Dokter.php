<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokter';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    // Relationship with dokter_spesialis
    public function spesialis(): BelongsTo
    {
        return $this->belongsTo(DokterSpesialis::class, 'idspesialis', 'id');
    }

    // Relationship with dokter_jadwal
    public function jadwal(): HasMany
    {
        return $this->hasMany(DokterJadwal::class, 'iddokter', 'id');
    }

    // Relationship with dokter_kuota
    public function kuota(): HasMany
    {
        return $this->hasMany(DokterKuota::class, 'iddokter', 'id');
    }

    // Accessor for status text
    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? 'Aktif' : 'Nonaktif';
    }

    // Accessor for spesialis name
    public function getSpesialisNameAttribute()
    {
        return $this->spesialis ? $this->spesialis->spesialis : 'Dokter Umum';
    }
}
