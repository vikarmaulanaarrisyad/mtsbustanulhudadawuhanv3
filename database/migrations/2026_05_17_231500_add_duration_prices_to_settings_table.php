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
            // Differentiated prices for the 17 premium modules
            $table->unsignedInteger('workflow_price_monthly')->default(29000);
            $table->unsignedInteger('workflow_price_yearly')->default(79000);

            $table->unsignedInteger('announcements_price_monthly')->default(19000);
            $table->unsignedInteger('announcements_price_yearly')->default(39000);

            $table->unsignedInteger('teachers_price_monthly')->default(29000);
            $table->unsignedInteger('teachers_price_yearly')->default(79000);

            $table->unsignedInteger('students_price_monthly')->default(29000);
            $table->unsignedInteger('students_price_yearly')->default(79000);

            $table->unsignedInteger('curriculum_price_monthly')->default(39000);
            $table->unsignedInteger('curriculum_price_yearly')->default(99000);

            $table->unsignedInteger('achievements_price_monthly')->default(25000);
            $table->unsignedInteger('achievements_price_yearly')->default(59000);

            $table->unsignedInteger('cbt_price_monthly')->default(49000);
            $table->unsignedInteger('cbt_price_yearly')->default(119000);

            $table->unsignedInteger('grades_price_monthly')->default(39000);
            $table->unsignedInteger('grades_price_yearly')->default(99000);

            $table->unsignedInteger('attendance_price_monthly')->default(49000);
            $table->unsignedInteger('attendance_price_yearly')->default(119000);

            $table->unsignedInteger('mail_price_monthly')->default(29000);
            $table->unsignedInteger('mail_price_yearly')->default(69000);

            $table->unsignedInteger('savings_price_monthly')->default(39000);
            $table->unsignedInteger('savings_price_yearly')->default(99000);

            $table->unsignedInteger('bos_price_monthly')->default(39000);
            $table->unsignedInteger('bos_price_yearly')->default(109000);

            $table->unsignedInteger('ppdb_price_monthly')->default(29000);
            $table->unsignedInteger('ppdb_price_yearly')->default(79000);

            $table->unsignedInteger('website_price_monthly')->default(25000);
            $table->unsignedInteger('website_price_yearly')->default(59000);

            $table->unsignedInteger('wa_gateway_price_monthly')->default(59000);
            $table->unsignedInteger('wa_gateway_price_yearly')->default(149000);

            $table->unsignedInteger('users_price_monthly')->default(19000);
            $table->unsignedInteger('users_price_yearly')->default(49000);

            $table->unsignedInteger('system_price_monthly')->default(49000);
            $table->unsignedInteger('system_price_yearly')->default(119000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'workflow_price_monthly', 'workflow_price_yearly',
                'announcements_price_monthly', 'announcements_price_yearly',
                'teachers_price_monthly', 'teachers_price_yearly',
                'students_price_monthly', 'students_price_yearly',
                'curriculum_price_monthly', 'curriculum_price_yearly',
                'achievements_price_monthly', 'achievements_price_yearly',
                'cbt_price_monthly', 'cbt_price_yearly',
                'grades_price_monthly', 'grades_price_yearly',
                'attendance_price_monthly', 'attendance_price_yearly',
                'mail_price_monthly', 'mail_price_yearly',
                'savings_price_monthly', 'savings_price_yearly',
                'bos_price_monthly', 'bos_price_yearly',
                'ppdb_price_monthly', 'ppdb_price_yearly',
                'website_price_monthly', 'website_price_yearly',
                'wa_gateway_price_monthly', 'wa_gateway_price_yearly',
                'users_price_monthly', 'users_price_yearly',
                'system_price_monthly', 'system_price_yearly'
            ]);
        });
    }
};
