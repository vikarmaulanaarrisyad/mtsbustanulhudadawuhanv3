<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Alpa',
            'permit' => 'Izin',
            'sick' => 'Sakit',
            'holiday' => 'Libur',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'present' => 'success',
            'late' => 'warning',
            'absent' => 'danger',
            'permit' => 'info',
            'sick' => 'primary',
            'holiday' => 'secondary',
        ];

        return $colors[$this->status] ?? 'dark';
    }
}
