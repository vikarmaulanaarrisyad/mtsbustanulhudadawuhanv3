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
        Schema::table('students', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->unique()->after('nisn');
        });

        // Initialize tokens for existing students
        \App\Models\Student::all()->each(function ($student) {
            $student->update(['qr_token' => \Illuminate\Support\Str::random(32)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
