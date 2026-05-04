<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassGroup extends Model
{
    use HasFactory;
    protected $table = 'class_groups';
    protected $guarded = [];

    public function getKelasLengkapAttribute()
    {
        return $this->class_group . ' - ' . $this->sub_class_group;
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
