<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke Guru/Staf yang memiliki jabatan ini.
     * Catatan: Jika ingin menggunakan foreign key di tabel teachers,
     * kita perlu menambahkan kolom position_id di migrasi berikutnya.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'position', 'name');
    }
}
