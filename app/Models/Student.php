<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function parents()
    {
        return $this->hasOne(StudentParent::class);
    }

    public function histories()
    {
        return $this->hasMany(StudentHistory::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'student_class_group_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studentStatus()
    {
        return $this->belongsTo(StudentStatus::class);
    }

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'student_residence_id');
    }

    // ==================== ACCESSORS ====================

    public function getKelasLengkapAttribute()
    {
        if ($this->classGroup) {
            return $this->classGroup->class_group . ' ' . $this->classGroup->sub_class_group;
        }
        return '-';
    }
}
