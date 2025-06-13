<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory;

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
