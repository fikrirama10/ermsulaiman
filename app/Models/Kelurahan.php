<?php

namespace App\Models;

use App\Models\Pasien\Alamat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = 'kelurahan';
    protected $primaryKey = 'id_kel';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false; // Assuming no timestamps based on SQL

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kec', 'id_kec');
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'idkel', 'id_kel');
    }
}
