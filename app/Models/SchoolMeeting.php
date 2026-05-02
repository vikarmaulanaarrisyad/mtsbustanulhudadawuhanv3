<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\AutoNumberTrait;

class SchoolMeeting extends Model
{
    use HasFactory, AutoNumberTrait;

    protected $guarded = [];

    protected $casts = [
        'mail_date' => 'date',
        'meeting_date' => 'date',
    ];
}
