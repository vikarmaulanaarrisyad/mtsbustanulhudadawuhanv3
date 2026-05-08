<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceIndicator extends Model
{
    protected $fillable = ['category', 'indicator_text', 'weight', 'target_role'];

    public function assessmentDetails()
    {
        return $this->hasMany(PerformanceAssessmentDetail::class);
    }
}
