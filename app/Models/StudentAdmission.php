<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAdmission extends Model
{
    use HasFactory;

    public function academicYear()
    {
        return $this->belongsTo(academicYear::class);
    }

    protected $casts = [
        'admission_start_date' => 'date',
        'admission_end_date' => 'date',
        'announcement_start_date' => 'date',
        'announcement_end_date' => 'date',
    ];

    /**
     * Cek apakah pengumuman sudah aktif berdasarkan tanggal.
     */
    public function isAnnouncementActive()
    {
        $now = now();
        // Pengumuman dianggap aktif jika hari ini sudah masuk tanggal mulai
        return $now->greaterThanOrEqualTo($this->announcement_start_date);
    }

    /**
     * Generate Nomor Surat Berita Acara.
     */
    public function generateBaLetterNumber()
    {
        if ($this->ba_letter_number) return $this->ba_letter_number;

        $mailSetting = \App\Models\MailSetting::first();
        $schoolCode = $mailSetting->school_code ?? 'MTs-BH';
        $year = date('Y');

        $lastNumber = self::where('ba_letter_number', 'like', "%/BA-PPDB/{$schoolCode}/{$year}")
            ->orderBy('ba_letter_number', 'desc')
            ->value('ba_letter_number');

        if ($lastNumber) {
            $lastSeq = (int) explode('/', $lastNumber)[0];
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        $this->ba_letter_number = str_pad($newSeq, 3, '0', STR_PAD_LEFT) . '/BA-PPDB/' . $schoolCode . '/' . $year;
        $this->save();

        return $this->ba_letter_number;
    }

    /**
     * Generate Nomor Surat SK Kolektif.
     */
    public function generateSkLetterNumber()
    {
        if ($this->sk_letter_number) return $this->sk_letter_number;

        $mailSetting = \App\Models\MailSetting::first();
        $schoolCode = $mailSetting->school_code ?? 'MTs-BH';
        $year = date('Y');

        $lastNumber = self::where('sk_letter_number', 'like', "%/SK-PPDB/{$schoolCode}/{$year}")
            ->orderBy('sk_letter_number', 'desc')
            ->value('sk_letter_number');

        if ($lastNumber) {
            $lastSeq = (int) explode('/', $lastNumber)[0];
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        $this->sk_letter_number = str_pad($newSeq, 3, '0', STR_PAD_LEFT) . '/SK-PPDB/' . $schoolCode . '/' . $year;
        $this->save();

        return $this->sk_letter_number;
    }
}
