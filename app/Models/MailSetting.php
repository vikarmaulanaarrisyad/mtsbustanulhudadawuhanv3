<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_code',
        'sub_header',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'header_line_style',
        'default_signer_name',
        'default_signer_position',
        'default_signer_nip'
    ];
}
