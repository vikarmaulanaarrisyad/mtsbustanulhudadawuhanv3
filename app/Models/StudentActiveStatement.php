<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\AutoNumberTrait;

class StudentActiveStatement extends Model
{
    use HasFactory, AutoNumberTrait;

    protected $casts = [
        'letter_date' => 'date',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_active_statement_details', 'statement_id', 'student_id')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
