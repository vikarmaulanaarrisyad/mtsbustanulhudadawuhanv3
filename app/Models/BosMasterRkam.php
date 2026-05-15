<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BosMasterRkam extends Model
{
    protected $fillable = [
        'kode_snp', 'snp', 
        'kode_kegiatan', 'nama_kegiatan', 
        'kode_sub_kegiatan', 'sub_kegiatan'
    ];
}
