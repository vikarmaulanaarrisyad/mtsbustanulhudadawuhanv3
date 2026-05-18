<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraduationSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'announcement_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
