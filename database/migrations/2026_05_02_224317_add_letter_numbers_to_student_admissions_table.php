<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_admissions', function (Blueprint $table) {
            $table->string('ba_letter_number')->nullable()->after('announcement_end_date');
            $table->string('sk_letter_number')->nullable()->after('ba_letter_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_admissions', function (Blueprint $table) {
            $table->dropColumn(['ba_letter_number', 'sk_letter_number']);
        });
    }
};
