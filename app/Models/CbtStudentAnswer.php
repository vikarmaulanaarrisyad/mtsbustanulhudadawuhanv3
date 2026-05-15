<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtStudentAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_doubtful' => 'boolean',
        'is_correct' => 'boolean',
        'selected_options' => 'array',
        'matching_answers' => 'array',
        'score' => 'float',
    ];

    public function studentExam()
    {
        return $this->belongsTo(CbtStudentExam::class, 'cbt_student_exam_id');
    }

    public function question()
    {
        return $this->belongsTo(CbtQuestion::class, 'cbt_question_id');
    }

    public function option()
    {
        return $this->belongsTo(CbtOption::class, 'cbt_option_id');
    }
}
