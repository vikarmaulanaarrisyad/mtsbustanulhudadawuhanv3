<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAssessmentDetail extends Model
{
    protected $fillable = ['performance_assessment_id', 'performance_indicator_id', 'score'];

    public function assessment()
    {
        return $this->belongsTo(PerformanceAssessment::class, 'performance_assessment_id');
    }

    public function indicator()
    {
        return $this->belongsTo(PerformanceIndicator::class, 'performance_indicator_id');
    }
}
