<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionQuotas extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'admission_phase_id',
        'admission_types_id',
        'quota',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function admissionPhase()
    {
        return $this->belongsTo(AdmissionPhase::class);
    }

    public function admissionTypes()
    {
        return $this->belongsTo(AdmissionType::class);
    }
}
