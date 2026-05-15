<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppdb_registrant_id',
        'user_id',
        'action',
        'description',
        'old_status',
        'new_status',
        'metadata',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Format created_at for JSON / API responses.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('d M Y, H:i');
    }

    public function registrant()
    {
        return $this->belongsTo(PpdbRegistrant::class, 'ppdb_registrant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
