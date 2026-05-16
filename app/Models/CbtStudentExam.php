<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtStudentExam extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'question_order' => 'array',
        'option_order' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(CbtStudentAnswer::class);
    }

    public function getCorrectAnswersAttribute()
    {
        return $this->answers()->where('is_correct', true)->count();
    }

    public function getWrongAnswersAttribute()
    {
        return $this->answers()->where('is_correct', false)->count();
    }

    public function getScoreAttribute()
    {
        return $this->final_score;
    }
}
