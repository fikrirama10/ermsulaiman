<?php

namespace App\Models\Farmasi;

use App\Models\Obat\Obat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanObatDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_obat_detail';
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id');
    }

    public function penjualan()
    {
        return $this->belongsTo(PenjualanObat::class, 'id_penjualan', 'id');
    }
}
