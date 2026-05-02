<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\AutoNumberTrait;

class DutyLetter extends Model
{
    use HasFactory, AutoNumberTrait;

    protected $guarded = [];

    protected $casts = [
        'letter_date' => 'date',
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'duty_letter_teachers')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
