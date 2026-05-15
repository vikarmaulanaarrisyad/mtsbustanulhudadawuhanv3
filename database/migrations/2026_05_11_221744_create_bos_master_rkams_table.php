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
        Schema::create('bos_master_rkams', function (Blueprint $table) {
            $table->id();
            $table->string('kode_snp')->nullable();
            $table->string('snp')->nullable();
            $table->string('kode_kegiatan')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('kode_sub_kegiatan')->nullable();
            $table->string('sub_kegiatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bos_master_rkams');
    }
};
