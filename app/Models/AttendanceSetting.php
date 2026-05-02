<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'work_days' => 'array',
    ];
}
