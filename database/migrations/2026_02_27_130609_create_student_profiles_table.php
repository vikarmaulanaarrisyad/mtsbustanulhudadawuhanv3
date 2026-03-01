<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS TAMBAHAN
            |--------------------------------------------------------------------------
            */
            $table->string('nik', 30)->nullable()->index();
            $table->string('no_kk', 30)->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | ALAMAT
            |--------------------------------------------------------------------------
            */
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();

            /*
            |--------------------------------------------------------------------------
            | KONTAK
            |--------------------------------------------------------------------------
            */
            $table->string('no_hp', 20)->nullable()->index();
            $table->string('email')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | DATA TAMBAHAN
            |--------------------------------------------------------------------------
            */
            $table->string('transportasi')->nullable();
            $table->string('jarak_rumah')->nullable();
            $table->string('tinggi_badan', 5)->nullable();
            $table->string('berat_badan', 5)->nullable();
            $table->string('golongan_darah', 5)->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOKUMENTASI
            |--------------------------------------------------------------------------
            */
            $table->string('foto')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
