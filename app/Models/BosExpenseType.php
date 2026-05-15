<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BosExpenseType extends Model
{
    protected $fillable = ['kode_kate', 'kategori', 'kode_jenis', 'jenis', 'deskripsi'];
}
