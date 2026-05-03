<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbRegistrant extends Model
{
    use HasFactory;
    use \App\Traits\AutoNumberTrait {
        generateLetterNumber as traitGenerateLetterNumber;
    }

    protected $fillable = [
        'user_id',
        'registration_number',
        'student_admission_id',
        'admission_phase_id',
        'admission_type_id',
        'nama_lengkap',
        'nisn',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_sekolah',
        'nama_ayah',
        'nama_ibu',
        'no_hp_ortu',
        'alamat',
        'foto',
        'status',
        'average_score',
        'distance_km',
        'selection_score',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
        'confirmed_at',
        'payment_proof',
        'admin_note',
        'payment_method',
        'payment_status',
        'payment_amount',
        'midtrans_snap_token',
        'midtrans_order_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
        'average_score' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'selection_score' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    protected $appends = ['status_label', 'status_color', 'jk_label', 'confirmed_at_formatted'];

    // ==================== CONSTANTS ====================

    const STATUS_PENDING = 'pending';
    const STATUS_BERKAS_LENGKAP = 'berkas_lengkap';
    const STATUS_BERKAS_TIDAK_LENGKAP = 'berkas_tidak_lengkap';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DAFTAR_ULANG = 'daftar_ulang';
    const STATUS_DAFTAR_ULANG_VERIFIED = 'daftar_ulang_terverifikasi';
    const STATUS_MOVED = 'sudah_masuk_siswa';
    const STATUS_CADANGAN = 'cadangan';
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
            self::STATUS_DAFTAR_ULANG => 'Menunggu Verifikasi Pembayaran',
            self::STATUS_DAFTAR_ULANG_VERIFIED => 'Daftar Ulang Terverifikasi',
            self::STATUS_MOVED => 'Sudah Jadi Siswa',
            self::STATUS_CADANGAN => 'Cadangan',
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
            self::STATUS_DAFTAR_ULANG => 'primary',
            self::STATUS_DAFTAR_ULANG_VERIFIED => 'success',
            self::STATUS_MOVED => 'dark',
            self::STATUS_CADANGAN => 'secondary',
            self::STATUS_DITOLAK => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Label status untuk tampilan publik/siswa (menunggu pengumuman).
     */
    public function getPublicStatusLabelAttribute()
    {
        $admission = $this->studentAdmission;
        $phase = $this->admissionPhase;
        
        $isAnnouncementActive = ($phase && $phase->announcement_date) 
            ? $phase->isAnnouncementActive() 
            : ($admission ? $admission->isAnnouncementActive() : false);

        // Jika status final (Diterima/Ditolak) tapi pengumuman belum aktif
        if (in_array($this->status, [self::STATUS_DITERIMA, self::STATUS_DITOLAK])) {
            if (!$isAnnouncementActive) {
                return 'Proses Seleksi';
            }
        }

        return $this->status_label;
    }

    /**
     * Color status untuk tampilan publik/siswa.
     */
    public function getPublicStatusColorAttribute()
    {
        $admission = $this->studentAdmission;
        $phase = $this->admissionPhase;

        $isAnnouncementActive = $phase ? $phase->isAnnouncementActive() : ($admission ? $admission->isAnnouncementActive() : false);

        if (in_array($this->status, [self::STATUS_DITERIMA, self::STATUS_DITOLAK])) {
            if (!$isAnnouncementActive) {
                return 'info';
            }
        }

        return $this->status_color;
    }

    // ==================== HELPERS ====================

    public function calculateSelectionScore()
    {
        $type = $this->admissionType;
        if (!$type) return 0;

        $typeName = strtolower($type->admission_type_name);

        if (str_contains($typeName, 'prestasi')) {
            // Ranking berdasarkan nilai rapor tertinggi
            return $this->average_score ?? 0;
        } elseif (str_contains($typeName, 'zonasi')) {
            // Ranking berdasarkan jarak terdekat (jarak makin kecil, skor makin besar)
            // Asumsi jarak max adalah 100km, skor = 1000 - (jarak * 10)
            return 1000 - (($this->distance_km ?? 100) * 10);
        }

        // Default fallback
        return $this->average_score ?? 0;
    }

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

    public static function generateLetterNumber($type = 'SK-PPDB', $column = 'letter_number')
    {
        return self::traitGenerateLetterNumber($type, $column);
    }
    public function getJkLabelAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'L' : 'P';
    }

    public function getConfirmedAtFormattedAttribute()
    {
        return $this->confirmed_at ? $this->confirmed_at->format('d M Y, H:i') : '-';
    }
}
