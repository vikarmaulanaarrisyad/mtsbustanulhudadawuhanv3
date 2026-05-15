<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BosItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun', 'kategori', 'kode_kateg', 'nama_kateg', 
        'kode_provi', 'kode_kabk', 'code', 'name', 
        'spesifikasi', 'satuan', 'jenis_pemb', 
        'harga_1', 'harga_2', 'harga_3', 'price', 'unit'
    ];
}
