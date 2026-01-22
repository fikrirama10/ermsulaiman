<?php

namespace App\Models;

use App\Models\RekapMedis\RekapMedis;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakLanjut extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'demo_tindak_lanjut';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'idrawat',
        'idrekapmedis',
        'tindak_lanjut',
        'tgl_tindak_lanjut',
        'poli_rujuk',
        'tujuan_tindak_lanjut',
        'catatan',
        'operasi',
        'tindakan_operasi',
        'iddokter',
        'prb',
        'nomor',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'prb' => 'array',
        'operasi' => 'integer',
        'tgl_tindak_lanjut' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the rawat record associated with the tindak lanjut.
     */
    public function rawat(): BelongsTo
    {
        return $this->belongsTo(Rawat::class, 'idrawat', 'id');
    }

    /**
     * Get the dokter record associated with the tindak lanjut.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'iddokter', 'id');
    }

    /**
     * Get the poli record associated with the tindak lanjut.
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'poli_rujuk', 'kode');
    }

    /**
     * Get the rekap medis record associated with the tindak lanjut.
     */
    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapMedis::class, 'idrekapmedis', 'id');
    }

    /**
     * Generate nomor otomatis berurutan per bulan
     * Format: nomor urut 1, 2, 3, dst
     *
     * @return int
     */
    public function generateNomorOtomatis(): int
    {
        $year = date('Y');
        $month = date('m');

        // Ambil nomor terakhir di bulan dan tahun yang sama
        $lastNumber = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->max('nomor');

        return ($lastNumber ?? 0) + 1;
    }

    /**
     * Generate full formatted nomor
     * Format: XXX/SKPD/MM/YYYY
     *
     * @param string $prefix (default: 'SKPD')
     * @return string
     */
    public function generateFormattedNomor(string $prefix = 'SKPD'): string
    {
        $year = date('Y');
        $month = date('m');
        $nomor = str_pad($this->nomor ?? $this->generateNomorOtomatis(), 3, '0', STR_PAD_LEFT);

        return sprintf('%s/%s/%s/%s', $nomor, $prefix, $month, $year);
    }

    /**
     * Get bulan in roman numerals
     *
     * @param int $bulan
     * @return string
     */
    public static function bulanRomawi(int $bulan): string
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romawi[$bulan] ?? 'Invalid';
    }

    /**
     * Scope query untuk tahun tertentu
     */
    public function scopeOfYear($query, int $year)
    {
        return $query->whereYear('created_at', $year);
    }

    /**
     * Scope query untuk bulan tertentu
     */
    public function scopeOfMonth($query, int $month)
    {
        return $query->whereMonth('created_at', $month);
    }

    /**
     * Scope query untuk tipe tindak lanjut tertentu
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('tindak_lanjut', $type);
    }

    /**
     * Accessor untuk status operasi (human readable)
     */
    public function getStatusOperasiAttribute(): string
    {
        if ($this->operasi === null) {
            return '-';
        }
        return $this->operasi == 1 ? 'Ya' : 'Tidak';
    }

    /**
     * Check if tindak lanjut is kontrol kembali
     */
    public function isKontrolKembali(): bool
    {
        return $this->tindak_lanjut === 'Kontrol Kembali';
    }

    /**
     * Check if tindak lanjut is rujuk
     */
    public function isRujuk(): bool
    {
        return $this->tindak_lanjut === 'Dirujuk';
    }

    /**
     * Check if tindak lanjut is rawat inap
     */
    public function isRawatInap(): bool
    {
        return $this->tindak_lanjut === 'Dirawat';
    }

    /**
     * Check if tindak lanjut is interm
     */
    public function isInterm(): bool
    {
        return $this->tindak_lanjut === 'Interm';
    }

    /**
     * Check if tindak lanjut is PRB
     */
    public function isPrb(): bool
    {
        return $this->tindak_lanjut === 'Prb';
    }

    /**
     * Check if tindak lanjut is meninggal
     */
    public function isMeninggal(): bool
    {
        return $this->tindak_lanjut === 'Meninggal';
    }
}
