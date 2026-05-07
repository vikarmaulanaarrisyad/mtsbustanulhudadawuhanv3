<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function bank()
    {
        return $this->belongsTo(CbtBank::class, 'cbt_bank_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassGroup::class, 'cbt_exam_classes', 'cbt_exam_id', 'class_group_id');
    }

    public function studentExams()
    {
        return $this->hasMany(CbtStudentExam::class);
    }
}
