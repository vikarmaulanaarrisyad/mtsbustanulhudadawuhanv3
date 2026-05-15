<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function curriculumTargets()
    {
        return $this->hasMany(CurriculumTarget::class);
    }
}
