<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BosIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'level',
        'date',
        'amount',
        'source',
        'description',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
