<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolMeeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'mail_date' => 'date',
        'meeting_date' => 'date',
    ];
}
