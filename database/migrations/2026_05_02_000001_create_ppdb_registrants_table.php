<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_registrants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 30)->unique();

            $table->foreignId('student_admission_id')
                ->constrained('student_admissions')
                ->cascadeOnDelete();

            $table->foreignId('admission_phase_id')
                ->nullable()
                ->constrained('admission_phases')
                ->nullOnDelete();

            $table->foreignId('admission_type_id')
                ->nullable()
                ->constrained('admission_types')
                ->nullOnDelete();

            // Data Diri
            $table->string('nama_lengkap', 150);
            $table->string('nisn', 30)->nullable();
            $table->string('nik', 30)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir');
            $table->string('asal_sekolah')->nullable();

            // Data Orang Tua
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('no_hp_ortu', 20)->nullable();

            // Alamat
            $table->text('alamat')->nullable();

            // Foto
            $table->string('foto')->nullable();

            // Status Verifikasi
            $table->enum('status', [
                'pending',
                'berkas_lengkap',
                'berkas_tidak_lengkap',
                'diterima',
                'ditolak'
            ])->default('pending');

            $table->text('catatan_verifikasi')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->datetime('verified_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('student_admission_id');
            $table->index('admission_phase_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrants');
    }
};
