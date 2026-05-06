<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function dutyLetters()
    {
        return $this->belongsToMany(DutyLetter::class, 'duty_letter_teachers');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
