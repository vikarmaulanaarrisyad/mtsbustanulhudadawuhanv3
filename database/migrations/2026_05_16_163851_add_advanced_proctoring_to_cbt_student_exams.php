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
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->text('admin_message')->nullable()->after('final_score');
            $table->boolean('is_logged_in')->default(false)->after('admin_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->dropColumn(['admin_message', 'is_logged_in']);
        });
    }
};
