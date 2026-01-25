<?php

namespace App\Models\Farmasi;

use App\Models\User;
use App\Models\Rawat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanObat extends Model
{
    use HasFactory;

    protected $table = 'penjualan_obat';
    protected $guarded = ['id'];

    public function detail()
    {
        return $this->hasMany(PenjualanObatDetail::class, 'id_penjualan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function rawat()
    {
        return $this->belongsTo(Rawat::class, 'id_rawat', 'id');
    }
}
