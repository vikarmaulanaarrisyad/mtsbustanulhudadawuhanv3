<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Study Periods (Master Jam Pelajaran)
        Schema::create('study_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('period_number'); // Jam ke-1, Jam ke-2, dst
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_break')->default(false); // Istirahat
            $table->timestamps();
        });

        // 2. Update Class Schedules to use Study Period
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->foreignId('study_period_id')->nullable()->constrained('study_periods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['study_period_id']);
            $table->dropColumn('study_period_id');
        });
        Schema::dropIfExists('study_periods');
    }
};
