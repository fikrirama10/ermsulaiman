<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBpjs extends Model
{
    protected $table = 'demo_log_request_bpjs';
    
    public $timestamps = false; // We manage created_at manually if needed, or let DB handle it if default

    protected $fillable = [
        'url',
        'methhod', // Note: Typo in database schema
        'time_request',
        'response',
        'message',
        'code',
        'name',
        'created_at',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
