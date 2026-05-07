<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'document_type',
        'document_number',
        'verification_code',
        'metadata',
        'signed_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
