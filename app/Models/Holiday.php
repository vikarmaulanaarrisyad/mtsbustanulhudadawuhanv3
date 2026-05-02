<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}
