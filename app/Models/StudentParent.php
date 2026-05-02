<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentParent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_parents';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fatherEducation()
    {
        return $this->belongsTo(Education::class, 'father_education_id');
    }

    public function motherEducation()
    {
        return $this->belongsTo(Education::class, 'mother_education_id');
    }

    public function fatherIncome()
    {
        return $this->belongsTo(MonthlyIncome::class, 'father_income_id');
    }

    public function motherIncome()
    {
        return $this->belongsTo(MonthlyIncome::class, 'mother_income_id');
    }
}
