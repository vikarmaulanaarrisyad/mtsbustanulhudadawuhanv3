<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionType extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function quota()
    {
        return $this->hasOne(AdmissionQuotas::class, 'admission_types_id');
    }
}
