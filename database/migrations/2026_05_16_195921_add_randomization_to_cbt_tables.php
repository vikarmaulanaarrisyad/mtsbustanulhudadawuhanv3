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
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->boolean('randomize_questions')->default(false)->after('is_active');
            $table->boolean('randomize_options')->default(false)->after('randomize_questions');
        });

        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->longText('question_order')->nullable()->after('status');
            $table->longText('option_order')->nullable()->after('question_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropColumn(['randomize_questions', 'randomize_options']);
        });

        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->dropColumn(['question_order', 'option_order']);
        });
    }
};
