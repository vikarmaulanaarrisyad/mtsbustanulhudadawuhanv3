<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbPaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'amount',
        'academic_year_id',
        'is_active',
        'description',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
