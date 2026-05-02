<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentHistory extends Model
{
    use HasFactory;

    protected $casts = [
        'entry_date' => 'date',
        'exit_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }
}
