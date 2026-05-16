<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtDutySchedule extends Model
{
    protected $guarded = [];

    public function exam()
    {
        return $this->belongsTo(CbtExam::class, 'cbt_exam_id');
    }

    public function proctor()
    {
        return $this->belongsTo(Teacher::class, 'proctor_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Teacher::class, 'supervisor_id');
    }
}
