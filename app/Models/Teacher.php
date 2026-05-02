<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dutyLetters()
    {
        return $this->belongsToMany(DutyLetter::class, 'duty_letter_teachers');
    }
}
