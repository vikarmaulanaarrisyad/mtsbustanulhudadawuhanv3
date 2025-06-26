<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionQuotas extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function admissionTypes()
    {
        return $this->belongsTo(AdmissionType::class);
    }
}
