<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table for Teachers/Staff
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->nullable();
            $table->string('name', 150);
            $table->string('position', 100)->nullable(); // Jabatan
            $table->string('rank', 100)->nullable();     // Pangkat/Golongan
            $table->timestamps();
        });

        // Table for Duty Letters (Surat Tugas)
        Schema::create('duty_letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number', 100)->unique();
            $table->date('letter_date');
            $table->text('purpose');               // Maksud Tugas
            $table->string('destination', 255);    // Tempat Tujuan
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->string('transportation', 100)->nullable();
            $table->string('budget_source', 100)->nullable(); // Sumber Anggaran
            
            // Signer
            $table->string('signer_name', 150)->nullable();
            $table->string('signer_position', 150)->nullable();
            $table->string('signer_nip', 30)->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for Teachers assigned to a Duty Letter
        Schema::create('duty_letter_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duty_letter_id')->constrained('duty_letters')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_letter_teachers');
        Schema::dropIfExists('duty_letters');
        Schema::dropIfExists('teachers');
    }
};
