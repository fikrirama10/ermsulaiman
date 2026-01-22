<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSatusehat extends Model
{
    protected $table = 'demo_log_satset';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'response',
        'time',
        'created_at',
        'user',
        'name',
        'method',
        'data',
        'code'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
