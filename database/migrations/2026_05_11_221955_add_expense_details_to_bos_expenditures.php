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
            $table->string('kode_kate')->nullable()->after('sub_kegiatan');
            $table->string('kategori')->nullable()->after('kode_kate');
            $table->string('kode_jenis')->nullable()->after('kategori');
            $table->string('jenis')->nullable()->after('kode_jenis');
            $table->text('deskripsi')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn(['kode_kate', 'kategori', 'kode_jenis', 'jenis', 'deskripsi']);
        });
    }
};
