<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawatTindakan extends Model
{
    use HasFactory;

    protected $table = 'rawat_tindakan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function rawat(): BelongsTo
    {
        return $this->belongsTo(Rawat::class, 'idrawat', 'id');
    }

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class, 'idtindakan', 'id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'iddokter', 'id');
    }

    public function bayar(): BelongsTo
    {
        return $this->belongsTo(Bayar::class, 'idbayar', 'id');
    }
}
