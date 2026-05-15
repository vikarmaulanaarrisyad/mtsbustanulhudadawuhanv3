<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BosExpenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'program_code',
        'program_name',
        'kode_snp',
        'snp',
        'kode_kegiatan',
        'nama_kegiatan',
        'kode_sub_kegiatan',
        'sub_kegiatan',
        'kode_kate',
        'kategori',
        'kode_jenis',
        'jenis',
        'deskripsi',
        'item_name',
        'item_code',
        'item_specification',
        'item_unit',
        'item_payment_type',
        'item_price_1',
        'item_price_2',
        'item_price_3',
        'level',
        'date',
        'amount',
        'category',
        'activity_code',
        'description',
        'receiver',
        'receipt_number',
        'noted_at',
        'realized_at',
        'expense_category',
        'expense_type',
        'evidence_path',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
