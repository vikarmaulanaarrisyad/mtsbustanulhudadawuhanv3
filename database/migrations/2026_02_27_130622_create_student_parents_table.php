<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_parents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FATHER DATA
            |--------------------------------------------------------------------------
            */
            $table->string('father_name')->nullable();
            $table->string('father_nik', 30)->nullable()->index();

            $table->foreignId('father_job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            $table->foreignId('father_education_id')
                ->nullable()
                ->constrained('educations')
                ->nullOnDelete();

            $table->foreignId('father_income_id')
                ->nullable()
                ->constrained('monthly_incomes')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | MOTHER DATA
            |--------------------------------------------------------------------------
            */
            $table->string('mother_name')->nullable();
            $table->string('mother_nik', 30)->nullable()->index();

            $table->foreignId('mother_job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            $table->foreignId('mother_education_id')
                ->nullable()
                ->constrained('educations')
                ->nullOnDelete();

            $table->foreignId('mother_income_id')
                ->nullable()
                ->constrained('monthly_incomes')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */
            $table->string('father_phone', 20)->nullable();
            $table->string('mother_phone', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | INDEX OPTIMIZATION
            |--------------------------------------------------------------------------
            */
            $table->index('father_job_id');
            $table->index('mother_job_id');
            $table->index('father_education_id');
            $table->index('mother_education_id');
            $table->index('father_income_id');
            $table->index('mother_income_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parents');
    }
};
