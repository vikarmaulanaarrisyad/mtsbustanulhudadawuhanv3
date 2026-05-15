<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'academic_year_id',
        'semester',
        'chapter_number',
        'title',
        'description',
        'is_active',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function journals()
    {
        return $this->hasMany(TeachingJournal::class);
    }
}
