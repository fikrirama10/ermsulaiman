<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kabupaten'; // Defined as per user request
    protected $primaryKey = 'id_kab';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'id_kab', 'id_kab');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_prov', 'id_prov');
    }
}
