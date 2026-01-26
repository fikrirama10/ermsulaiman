<?php

namespace App\Models\Pasien;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pasien\Pasien;
use App\Models\Provinsi;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'pasien_alamat';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    /**
     * Get the user that owns the Alamat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'idkel', 'id_kel');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'idkec', 'id_kec');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'idkab', 'id_kab');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'idprov', 'id_prov');
    }
}
