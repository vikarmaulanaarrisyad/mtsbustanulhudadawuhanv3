<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoNumberTrait;

class StudentAcceptance extends Model
{
    use HasFactory, AutoNumberTrait;

    protected $fillable = [
        'student_id',
        'acceptance_number',
        'acceptance_date',
        'origin_school',
        'origin_class',
        'signer_name',
        'signer_position',
        'signer_nip',
        'created_by'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
