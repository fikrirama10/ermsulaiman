<?php

namespace App\Models;

use App\Models\Pasien\Pasien;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawatKunjungan extends Model
{
    use HasFactory;

    protected $table = 'rawat_kunjungan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function rawat(): HasMany
    {
        return $this->hasMany(Rawat::class, 'idkunjungan', 'idkunjungan');
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'idpasien', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
