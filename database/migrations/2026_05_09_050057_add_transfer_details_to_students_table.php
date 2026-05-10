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
        Schema::table('students', function (Blueprint $table) {
            $table->string('pindah_ke')->nullable()->after('asal_sekolah');
            $table->string('alasan_pindah')->nullable()->after('pindah_ke');
            $table->string('surat_pindah_number')->nullable()->after('skl_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['pindah_ke', 'alasan_pindah', 'surat_pindah_number']);
        });
    }
};
