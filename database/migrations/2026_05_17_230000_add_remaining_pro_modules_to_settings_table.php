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
        Schema::table('settings', function (Blueprint $table) {
            // Status columns
            $table->boolean('is_announcements_pro_active')->default(0);
            $table->boolean('is_students_pro_active')->default(0);
            $table->boolean('is_curriculum_pro_active')->default(0);
            $table->boolean('is_achievements_pro_active')->default(0);
            $table->boolean('is_grades_pro_active')->default(0);
            $table->boolean('is_attendance_pro_active')->default(0);
            $table->boolean('is_mail_pro_active')->default(0);
            $table->boolean('is_bos_pro_active')->default(0);
            $table->boolean('is_wa_gateway_pro_active')->default(0);
            $table->boolean('is_users_pro_active')->default(0);
            $table->boolean('is_system_pro_active')->default(0);

            // Price columns
            $table->unsignedInteger('announcements_price')->default(49000);
            $table->unsignedInteger('students_price')->default(99000);
            $table->unsignedInteger('curriculum_price')->default(119000);
            $table->unsignedInteger('achievements_price')->default(79000);
            $table->unsignedInteger('grades_price')->default(129000);
            $table->unsignedInteger('attendance_price')->default(149000);
            $table->unsignedInteger('mail_price')->default(89000);
            $table->unsignedInteger('bos_price')->default(139000);
            $table->unsignedInteger('wa_gateway_price')->default(199000);
            $table->unsignedInteger('users_price')->default(69000);
            $table->unsignedInteger('system_price')->default(149000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'is_announcements_pro_active',
                'is_students_pro_active',
                'is_curriculum_pro_active',
                'is_achievements_pro_active',
                'is_grades_pro_active',
                'is_attendance_pro_active',
                'is_mail_pro_active',
                'is_bos_pro_active',
                'is_wa_gateway_pro_active',
                'is_users_pro_active',
                'is_system_pro_active',

                'announcements_price',
                'students_price',
                'curriculum_price',
                'achievements_price',
                'grades_price',
                'attendance_price',
                'mail_price',
                'bos_price',
                'wa_gateway_price',
                'users_price',
                'system_price',
            ]);
        });
    }
};
