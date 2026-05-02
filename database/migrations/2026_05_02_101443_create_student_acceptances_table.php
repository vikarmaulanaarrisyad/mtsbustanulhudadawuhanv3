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
        Schema::create('student_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('acceptance_number')->unique();
            $table->date('acceptance_date');
            $table->string('origin_school'); // Sekolah Asal
            $table->string('origin_class')->nullable(); // Kelas di Sekolah Asal
            $table->string('signer_name')->nullable();
            $table->string('signer_position')->nullable();
            $table->string('signer_nip')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_acceptances');
    }
};
