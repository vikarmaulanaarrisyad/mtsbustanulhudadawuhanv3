<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionPhase extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
