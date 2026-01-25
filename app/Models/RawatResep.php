<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawatResep extends Model
{
    use HasFactory;

    protected $table = 'rawat_resep';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function rawat(): BelongsTo
    {
        return $this->belongsTo(Rawat::class, 'idrawat', 'id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'iddokter', 'id');
    }
}
