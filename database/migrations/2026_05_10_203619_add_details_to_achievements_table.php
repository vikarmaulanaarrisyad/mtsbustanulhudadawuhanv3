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
        Schema::table('achievements', function (Blueprint $table) {
            $table->foreignId('student_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->after('student_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_name')->after('title')->nullable();
            $table->string('category')->after('event_name')->nullable(); // Akademik, Non-Akademik
            $table->string('level')->after('category')->nullable(); // Sekolah, Kecamatan, Kabupaten, Provinsi, Nasional, Internasional
            $table->date('date')->after('year')->nullable();
            $table->text('description')->after('date')->nullable();
            $table->string('certificate_path')->after('image')->nullable();
            $table->string('trophy_path')->after('certificate_path')->nullable();
            $table->string('status')->default('approved')->after('trophy_path'); // pending, approved, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn([
                'student_id', 'academic_year_id', 'event_name', 'category', 
                'level', 'date', 'description', 'certificate_path', 
                'trophy_path', 'status'
            ]);
        });
    }
};
