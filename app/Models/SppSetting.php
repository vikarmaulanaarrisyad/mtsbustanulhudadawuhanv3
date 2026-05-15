<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_level',
        'amount',
        'academic_year_id',
        'description',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
