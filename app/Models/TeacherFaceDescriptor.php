<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherFaceDescriptor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'descriptors' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
