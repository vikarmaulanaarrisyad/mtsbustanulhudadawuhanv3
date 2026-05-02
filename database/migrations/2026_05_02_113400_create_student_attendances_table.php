<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('class_group_id')->constrained('class_groups')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['present', 'late', 'absent', 'permit', 'sick'])->default('present');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
