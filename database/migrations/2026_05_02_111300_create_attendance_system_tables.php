<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add user_id to teachers for login link
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
        });

        // 2. Attendance Settings
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('check_in_start')->default('06:00:00');
            $table->time('check_in_end')->default('08:00:00');
            $table->time('check_out_start')->default('14:00:00');
            $table->time('check_out_end')->default('17:00:00');
            $table->string('work_days')->default('1,2,3,4,5,6'); // 1=Mon, 6=Sat
            $table->timestamps();
        });

        // 3. National Holidays
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // 4. Attendances Logs
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'permit', 'sick', 'holiday'])->default('present');
            $table->text('notes')->nullable();
            $table->string('check_in_ip')->nullable();
            $table->string('check_out_ip')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('attendance_settings');
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
