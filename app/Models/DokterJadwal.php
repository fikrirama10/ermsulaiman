<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokterJadwal extends Model
{
    use HasFactory;
    protected $table = 'dokter_jadwal';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationship with dokter
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'iddokter', 'id');
    }

    // Relationship with poli
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'idpoli', 'id');
    }

    // Accessor for hari nama
    public function getHariNamaAttribute()
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return $hari[$this->hari] ?? '-';
    }

    // Accessor for waktu
    public function getWaktuAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge badge-success">Aktif</span>';
        } else {
            return '<span class="badge badge-secondary">Nonaktif</span>';
        }
    }
}
