<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'punch_time',
        'status'
    ];

    protected $casts = [
        'punch_time' => 'datetime',
    ];
}
