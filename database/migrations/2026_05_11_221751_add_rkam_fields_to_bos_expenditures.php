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
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->string('kode_snp')->nullable()->after('academic_year_id');
            $table->string('snp')->nullable()->after('kode_snp');
            $table->string('kode_kegiatan')->nullable()->after('snp');
            $table->string('nama_kegiatan')->nullable()->after('kode_kegiatan');
            $table->string('kode_sub_kegiatan')->nullable()->after('nama_kegiatan');
            $table->string('sub_kegiatan')->nullable()->after('kode_sub_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn(['kode_snp', 'snp', 'kode_kegiatan', 'nama_kegiatan', 'kode_sub_kegiatan', 'sub_kegiatan']);
        });
    }
};
