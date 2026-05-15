<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bos_items', function (Blueprint $table) {
            $table->string('tahun')->nullable()->after('id');
            $table->string('kategori')->nullable()->after('tahun');
            $table->string('kode_kateg')->nullable()->after('kategori');
            $table->string('nama_kateg')->nullable()->after('kode_kateg');
            $table->string('kode_provi')->nullable()->after('nama_kateg');
            $table->string('kode_kabk')->nullable()->after('kode_provi');
            $table->string('spesifikasi')->nullable()->after('name');
            $table->string('satuan')->nullable()->after('spesifikasi');
            $table->string('jenis_pemb')->nullable()->after('satuan');
            $table->decimal('harga_1', 15, 2)->nullable()->after('jenis_pemb');
            $table->decimal('harga_2', 15, 2)->nullable()->after('harga_1');
            $table->decimal('harga_3', 15, 2)->nullable()->after('harga_2');
        });
    }

    public function down(): void
    {
        Schema::table('bos_items', function (Blueprint $table) {
            $table->dropColumn([
                'tahun', 'kategori', 'kode_kateg', 'nama_kateg', 'kode_provi', 'kode_kabk', 
                'spesifikasi', 'satuan', 'jenis_pemb', 'harga_1', 'harga_2', 'harga_3'
            ]);
        });
    }
};
