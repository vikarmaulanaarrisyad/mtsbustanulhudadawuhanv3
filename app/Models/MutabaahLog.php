<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutabaahLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'shubuh' => 'boolean',
        'zhuhur' => 'boolean',
        'ashar' => 'boolean',
        'maghrib' => 'boolean',
        'isya' => 'boolean',
        'dhuha' => 'boolean',
        'tahajud' => 'boolean',
        'is_validated_by_parent' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
