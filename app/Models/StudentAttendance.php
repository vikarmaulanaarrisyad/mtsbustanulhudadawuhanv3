<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
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

    public function getStatusLabelAttribute()
    {
        $labels = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Alpa',
            'permit' => 'Izin',
            'sick' => 'Sakit',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'present' => 'success',
            'late' => 'warning',
            'absent' => 'danger',
            'permit' => 'info',
            'sick' => 'primary',
        ];

        return $colors[$this->status] ?? 'dark';
    }
}
