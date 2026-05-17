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
            if (!Schema::hasColumn('settings', 'workflow_expires_at')) {
                $table->dateTime('workflow_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'announcements_expires_at')) {
                $table->dateTime('announcements_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'teachers_expires_at')) {
                $table->dateTime('teachers_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'students_expires_at')) {
                $table->dateTime('students_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'curriculum_expires_at')) {
                $table->dateTime('curriculum_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'achievements_expires_at')) {
                $table->dateTime('achievements_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'cbt_expires_at')) {
                $table->dateTime('cbt_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'grades_expires_at')) {
                $table->dateTime('grades_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'attendance_expires_at')) {
                $table->dateTime('attendance_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'mail_expires_at')) {
                $table->dateTime('mail_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'savings_expires_at')) {
                $table->dateTime('savings_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'bos_expires_at')) {
                $table->dateTime('bos_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'ppdb_expires_at')) {
                $table->dateTime('ppdb_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'website_expires_at')) {
                $table->dateTime('website_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'wa_gateway_expires_at')) {
                $table->dateTime('wa_gateway_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'users_expires_at')) {
                $table->dateTime('users_expires_at')->nullable();
            }
            if (!Schema::hasColumn('settings', 'system_expires_at')) {
                $table->dateTime('system_expires_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'workflow_expires_at',
                'announcements_expires_at',
                'teachers_expires_at',
                'students_expires_at',
                'curriculum_expires_at',
                'achievements_expires_at',
                'cbt_expires_at',
                'grades_expires_at',
                'attendance_expires_at',
                'mail_expires_at',
                'savings_expires_at',
                'bos_expires_at',
                'ppdb_expires_at',
                'website_expires_at',
                'wa_gateway_expires_at',
                'users_expires_at',
                'system_expires_at',
            ]);
        });
    }
};
