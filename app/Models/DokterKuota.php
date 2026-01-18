<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokterKuota extends Model
{
    use HasFactory;
    protected $table = 'dokter_kuota';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'tanggal' => 'date',
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

    // Accessor for tanggal formatted
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '-';
    }

    // Accessor for hari nama
    public function getHariNamaAttribute()
    {
        if (!$this->tanggal) return '-';

        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $dayName = Carbon::parse($this->tanggal)->format('l');
        return $hari[$dayName] ?? '-';
    }

    // Accessor for progress
    public function getProgressAttribute()
    {
        $terpakai = $this->kuota_terpakai ?? 0;
        $total = $this->kuota ?? 1;
        $percentage = ($terpakai / $total) * 100;

        $colorClass = 'bg-success';
        if ($percentage > 80) {
            $colorClass = 'bg-danger';
        } elseif ($percentage > 60) {
            $colorClass = 'bg-warning';
        }

        return '<div class="progress" style="height: 20px;">
                    <div class="progress-bar ' . $colorClass . '" role="progressbar"
                         style="width: ' . $percentage . '%;"
                         aria-valuenow="' . $terpakai . '"
                         aria-valuemin="0"
                         aria-valuemax="' . $total . '">
                        ' . $terpakai . '/' . $total . '
                    </div>
                </div>';
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
