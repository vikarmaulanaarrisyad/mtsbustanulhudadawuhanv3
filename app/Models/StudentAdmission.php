<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAdmission extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(academicYear::class);
    }
}
