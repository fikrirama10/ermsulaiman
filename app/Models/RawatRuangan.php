<?php

namespace App\Models;

use App\Models\Ruangan\Ruangan;
use App\Models\Ruangan\RuanganKelas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawatRuangan extends Model
{
    use HasFactory;

    protected $table = 'rawat_ruangan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function rawat(): BelongsTo
    {
        return $this->belongsTo(Rawat::class, 'idrawat', 'id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'idruangan', 'id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(RuanganKelas::class, 'idkelas', 'id');
    }

    public function bayar(): BelongsTo
    {
        return $this->belongsTo(Bayar::class, 'idbayar', 'id');
    }
}
