<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $guarded = ['id'];

    // Accessor for status text
    public function getStatusTextAttribute()
    {
        return $this->status == 1 ? 'Aktif' : 'Nonaktif';
    }

    public function user_detail()
    {
        return $this->hasOne(UserDetail::class, 'idkaryawan', 'id');
    }
}
