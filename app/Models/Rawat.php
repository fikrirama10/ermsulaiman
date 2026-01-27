<?php

namespace App\Models;

use App\Models\Pasien\Pasien;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rawat extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'rawat';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function poli(): HasOne
    {
        return $this->hasOne(Poli::class, 'id', 'idpoli');
    }

    public function dokter(): HasOne
    {
        return $this->hasOne(Dokter::class, 'id', 'iddokter');
    }

    public function bayar(): HasOne
    {
        return $this->hasOne(Bayar::class, 'id', 'idbayar');
    }
    public function pasien(): HasOne
    {
        return $this->hasOne(Pasien::class, 'no_rm', 'no_rm');
    }
    public function ruangan(): HasOne
    {
        return $this->hasOne(Ruangan::class, 'id', 'idruangan');
    }

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(RawatKunjungan::class, 'idkunjungan', 'idkunjungan');
    }

    public function riwayat_ruangan(): HasMany
    {
        return $this->hasMany(RawatRuangan::class, 'idrawat', 'id');
    }

    public function riwayat_tindakan(): HasMany
    {
        return $this->hasMany(RawatTindakan::class, 'idrawat', 'id');
    }

    public function riwayat_resep(): HasMany
    {
        return $this->hasMany(RawatResep::class, 'idrawat', 'id');
    }

    public function laborRecord(): HasOne
    {
        return $this->hasOne(\App\Models\Partograf\LaborRecord::class, 'visit_id', 'id');
    }

    /**
     * Scope untuk filter kasus persalinan
     */
    public function scopeMelahirkan($query)
    {
        return $query->where('melahirkan', 1);
    }

    public function spri(): HasOne
    {
        return $this->hasOne(RawatSpri::class, 'idrawat', 'id');
    }
}
