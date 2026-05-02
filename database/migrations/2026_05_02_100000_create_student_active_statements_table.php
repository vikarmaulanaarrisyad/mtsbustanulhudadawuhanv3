<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_active_statements', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->unique();
            $table->date('letter_date');
            $table->enum('type', ['individual', 'collective'])->default('individual');
            $table->string('purpose')->nullable();
            $table->string('signer_name')->nullable();
            $table->string('signer_position')->nullable();
            $table->string('signer_nip', 30)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('student_active_statement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_id')->constrained('student_active_statements')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_active_statement_details');
        Schema::dropIfExists('student_active_statements');
    }
};
