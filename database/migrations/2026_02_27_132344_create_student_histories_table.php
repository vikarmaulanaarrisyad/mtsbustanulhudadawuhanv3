<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_histories', function (Blueprint $table) {
            $table->id();

            // ==========================
            // RELATIONSHIPS
            // ==========================

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->foreignId('class_group_id')
                ->nullable() // WAJIB sebelum constrained
                ->constrained('class_groups')
                ->nullOnDelete();

            // ==========================
            // STATUS HISTORY
            // ==========================

            $table->enum('status', [
                'enrolled',
                'promoted',
                'retained',
                'graduated',
                'transferred',
                'dropped_out'
            ]);

            $table->date('entry_date')->nullable();
            $table->date('exit_date')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // ==========================
            // INDEX OPTIMIZATION
            // ==========================

            $table->index(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_histories');
    }
};
