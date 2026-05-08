<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAssessment extends Model
{
    protected $fillable = [
        'teacher_id', 
        'assessor_id', 
        'assessor_type', 
        'academic_year_id', 
        'total_score', 
        'status', 
        'notes'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function details()
    {
        return $this->hasMany(PerformanceAssessmentDetail::class);
    }
}
