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
        Schema::create('tahfidz_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date');
            
            $table->string('surah_name');
            $table->string('verse_range')->nullable();
            $table->integer('juz')->nullable();
            $table->enum('type', ['ziyadah', 'murojaah'])->default('ziyadah');
            $table->char('grade', 2)->nullable(); // A, B+, B, C, etc
            $table->integer('tajwid_score')->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfidz_logs');
    }
};
