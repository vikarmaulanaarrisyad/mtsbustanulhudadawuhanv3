<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS UTAMA
            |--------------------------------------------------------------------------
            */
            $table->string('nis', 30)->unique();
            $table->string('nisn', 30)->unique()->nullable();
            $table->string('nik', 30)->nullable()->index();
            $table->string('no_kk', 30)->nullable();

            $table->string('nama_lengkap', 150)->index();
            $table->string('nama_panggilan', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir');

            /*
            |--------------------------------------------------------------------------
            | DATA TAMBAHAN PRIBADI
            |--------------------------------------------------------------------------
            */
            // $table->foreignId('religion_id')
            //     ->nullable()
            //     ->constrained('religions')
            //     ->nullOnDelete();

            $table->foreignId('student_residence_id')
                ->nullable()
                ->constrained('residences')
                ->nullOnDelete();

            // $table->foreignId('child_status_id')
            //     ->nullable()
            //     ->constrained('child_statuses')
            //     ->nullOnDelete(); // Kandung / Angkat / Tiri

            $table->unsignedTinyInteger('anak_ke')->nullable();
            $table->unsignedTinyInteger('jumlah_saudara')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DATA AKADEMIK
            |--------------------------------------------------------------------------
            */
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('student_status_id')
                ->nullable()
                ->constrained('student_statuses')
                ->nullOnDelete(); // Aktif / Lulus / Pindah / DO

            $table->foreignId('student_class_group_id')
                ->nullable()
                ->constrained('class_groups')
                ->nullOnDelete();

            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DATA SEKOLAH ASAL
            |--------------------------------------------------------------------------
            */
            $table->string('asal_sekolah')->nullable();
            $table->string('no_ijazah')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DATA ADMINISTRASI
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMASI BESAR
            |--------------------------------------------------------------------------
            */
            $table->index('student_status_id');
            $table->index('student_class_group_id');
            $table->index('academic_year_id');
            $table->index(['academic_year_id', 'student_class_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
