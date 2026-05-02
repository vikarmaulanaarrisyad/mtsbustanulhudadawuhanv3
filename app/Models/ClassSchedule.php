<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function studyPeriod()
    {
        return $this->belongsTo(StudyPeriod::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $days[$this->day] ?? $this->day;
    }
}
