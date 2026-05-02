<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbRegistrant extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    // ==================== CONSTANTS ====================

    const STATUS_PENDING = 'pending';
    const STATUS_BERKAS_LENGKAP = 'berkas_lengkap';
    const STATUS_BERKAS_TIDAK_LENGKAP = 'berkas_tidak_lengkap';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DITOLAK = 'ditolak';

    const DOCUMENT_TYPES = [
        'akta' => 'Akta Kelahiran',
        'kk' => 'Kartu Keluarga (KK)',
        'ijazah' => 'Ijazah / SKL',
        'skhun' => 'SKHUN / Sertifikat Hasil Ujian',
        'rapor' => 'Rapor Terakhir',
        'foto' => 'Pas Foto 3x4',
        'kip' => 'Kartu Indonesia Pintar (KIP)',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function studentAdmission()
    {
        return $this->belongsTo(StudentAdmission::class);
    }

    public function admissionPhase()
    {
        return $this->belongsTo(AdmissionPhase::class);
    }

    public function admissionType()
    {
        return $this->belongsTo(AdmissionType::class);
    }

    public function verifier()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by');
    }

    public function documents()
    {
        return $this->hasMany(PpdbDocument::class);
    }

    // ==================== SCOPES ====================

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPhase($query, $phaseId)
    {
        return $query->where('admission_phase_id', $phaseId);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('admission_type_id', $typeId);
    }

    // ==================== ACCESSORS ====================

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_BERKAS_LENGKAP => 'Berkas Lengkap',
            self::STATUS_BERKAS_TIDAK_LENGKAP => 'Berkas Tidak Lengkap',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DITOLAK => 'Ditolak',
            default => '-',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_BERKAS_LENGKAP => 'info',
            self::STATUS_BERKAS_TIDAK_LENGKAP => 'danger',
            self::STATUS_DITERIMA => 'success',
            self::STATUS_DITOLAK => 'dark',
            default => 'secondary',
        };
    }

    // ==================== HELPERS ====================

    public static function generateRegistrationNumber($admissionYear)
    {
        $lastNumber = self::where('registration_number', 'like', "PPDB-{$admissionYear}-%")
            ->orderBy('registration_number', 'desc')
            ->value('registration_number');

        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber, -5);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        return 'PPDB-' . $admissionYear . '-' . str_pad($newSeq, 5, '0', STR_PAD_LEFT);
    }

    public static function generateLetterNumber()
    {
        $mailSetting = \App\Models\MailSetting::first();
        $schoolCode = $mailSetting->school_code ?? 'MTs-BH';
        $year = date('Y');

        $lastLetter = self::where('letter_number', 'like', "%/PPDB/{$schoolCode}/{$year}")
            ->orderBy('letter_number', 'desc')
            ->value('letter_number');

        if ($lastLetter) {
            $lastSeq = (int) explode('/', $lastLetter)[0];
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        return str_pad($newSeq, 3, '0', STR_PAD_LEFT) . '/PPDB/' . $schoolCode . '/' . $year;
    }
}
