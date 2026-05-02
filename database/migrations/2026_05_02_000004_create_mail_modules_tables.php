<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('sub_header')->nullable(); // Misal: Yayasan Pendidikan ...
            $table->string('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('logo')->nullable();
            $table->string('header_line_style')->default('double'); // solid, double, dashed
            $table->timestamps();
        });

        Schema::create('outgoing_mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail_number')->unique();
            $table->date('mail_date');
            $table->string('mail_subject');
            $table->string('mail_recipient');
            $table->longText('mail_content');
            $table->string('signer_name')->nullable();
            $table->string('signer_nip', 30)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('student_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->date('certificate_date');
            $table->string('purpose')->nullable(); // Keperluan
            $table->string('signer_name')->nullable();
            $table->string('signer_nip', 30)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_certificates');
        Schema::dropIfExists('outgoing_mails');
        Schema::dropIfExists('mail_settings');
    }
};
