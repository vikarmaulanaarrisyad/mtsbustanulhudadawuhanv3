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
        // 1. Bank Soal
        Schema::create('cbt_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Bank Soal Matematika Kelas 7"
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->integer('class_level'); // 7, 8, 9
            $table->timestamps();
        });

        // 2. Pertanyaan
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_bank_id')->constrained('cbt_banks')->onDelete('cascade');
            $table->longText('question_text');
            $table->string('question_image')->nullable();
            $table->integer('score_weight')->default(1);
            $table->timestamps();
        });

        // 3. Pilihan Ganda (Opsi)
        Schema::create('cbt_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_question_id')->constrained('cbt_questions')->onDelete('cascade');
            $table->text('option_text');
            $table->string('option_image')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // 4. Jadwal Ujian
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Ujian Tengah Semester Ganjil"
            $table->foreignId('cbt_bank_id')->constrained('cbt_banks')->onDelete('cascade');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes');
            $table->string('token', 6)->unique(); // Random 6 digit string
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Relasi Ujian ke Rombel (Kelas)
        Schema::create('cbt_exam_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_exam_id')->constrained('cbt_exams')->onDelete('cascade');
            $table->foreignId('class_group_id')->constrained('class_groups')->onDelete('cascade');
        });

        // 6. Status Pengerjaan Ujian Siswa
        Schema::create('cbt_student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_exam_id')->constrained('cbt_exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['not_started', 'doing', 'finished'])->default('not_started');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('violation_count')->default(0); // Anti-cheat trigger count
            $table->decimal('final_score', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['cbt_exam_id', 'student_id']);
        });

        // 7. Jawaban Siswa per Soal
        Schema::create('cbt_student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_student_exam_id')->constrained('cbt_student_exams')->onDelete('cascade');
            $table->foreignId('cbt_question_id')->constrained('cbt_questions')->onDelete('cascade');
            $table->foreignId('cbt_option_id')->nullable()->constrained('cbt_options')->onDelete('set null');
            $table->boolean('is_doubtful')->default(false); // Ragu-ragu
            $table->boolean('is_correct')->nullable(); // Ditentukan saat auto-grading
            $table->timestamps();
            
            $table->unique(['cbt_student_exam_id', 'cbt_question_id'], 'exam_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_student_answers');
        Schema::dropIfExists('cbt_student_exams');
        Schema::dropIfExists('cbt_exam_classes');
        Schema::dropIfExists('cbt_exams');
        Schema::dropIfExists('cbt_options');
        Schema::dropIfExists('cbt_questions');
        Schema::dropIfExists('cbt_banks');
    }
};
