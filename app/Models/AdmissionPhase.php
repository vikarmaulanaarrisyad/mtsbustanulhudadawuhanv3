<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionPhase extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    protected $casts = [
        'phase_start_date' => 'date',
        'phase_end_date' => 'date',
        'announcement_date' => 'date',
    ];

    /**
     * Cek apakah pengumuman pada gelombang ini sudah aktif.
     */
    public function isAnnouncementActive()
    {
        if (!$this->announcement_date) return false;
        return now()->greaterThanOrEqualTo($this->announcement_date);
    }
}
