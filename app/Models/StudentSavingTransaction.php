<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSavingTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function studentSaving()
    {
        return $this->belongsTo(StudentSaving::class, 'student_saving_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateReferenceNo($type)
    {
        $prefix = $type == 'debit' ? 'DEP-' : 'WDR-';
        return $prefix . date('YmdHis') . rand(100, 999);
    }
}
