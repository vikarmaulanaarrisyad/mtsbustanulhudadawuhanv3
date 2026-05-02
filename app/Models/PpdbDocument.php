<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbDocument extends Model
{
    use HasFactory;

    public function registrant()
    {
        return $this->belongsTo(PpdbRegistrant::class, 'ppdb_registrant_id');
    }
}
