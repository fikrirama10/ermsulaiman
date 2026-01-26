<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_surat' => 'date',
        'konten' => 'object', // Cast to object for easy property access
    ];

    // Relationships
    public function pasien()
    {
        // Adjust model path if different (e.g., App\Models\Pasien\Pasien or App\Models\Pasien)
        // Based on file list, Pasien is a directory, check Pasien.php location or use raw table in controller if model is complex
        // Assuming standard User or specific Pasien model exists.
        return $this->belongsTo(\App\Models\Pasien\Pasien::class, 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helpers to access content easily

}
