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
        // 1. Grade Settings (Konfigurasi Mapel per Jenjang)
        Schema::create('grade_settings', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // MI, MTs, MA
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('type'); // raport, ujian_madrasah
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Student Grades (Data Nilai Siswa)
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('type'); // raport, ujian_madrasah
            $table->integer('class_level'); // 4, 5, 6, 7, 8, 9, 10, 11, 12
            $table->integer('semester')->nullable(); // 1, 2 (null for ujian_madrasah)
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamps();

            // Index for faster lookup
            $table->index(['student_id', 'type', 'class_level', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('grade_settings');
    }
};
