<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kec';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'id_kec', 'id_kec');
    }

    public function kota()
    {
        // Assuming Kota corresponds to `id_kab` based on usual schema
        return $this->belongsTo(Kota::class, 'id_kab', 'id_kab');
    }
}
