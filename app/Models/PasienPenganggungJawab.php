<?php

namespace App\Models;

use App\Models\Pasien\Pasien;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienPenganggungJawab extends Model
{
    use HasFactory;

    protected $table = 'pasien_penanggungjawab';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'idsb_penanggungjawab');
    }
}
