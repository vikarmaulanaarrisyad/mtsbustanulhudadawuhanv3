<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds multi-type question support to CBT tables.
     */
    public function up(): void
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->enum('question_type', [
                'pilihan_ganda',    // PG - Single correct answer
                'ganda_komplek',    // PGK - Multiple correct answers
                'penjodohan',       // Matching pairs
                'essay',            // Short essay
                'uraian'            // Long descriptive answer
            ])->default('pilihan_ganda')->after('question_text');

            $table->json('matching_pairs')->nullable()->after('question_image');
            $table->text('answer_key')->nullable()->after('matching_pairs');
        });

        Schema::table('cbt_student_answers', function (Blueprint $table) {
            $table->json('selected_options')->nullable()->after('cbt_option_id');
            $table->longText('answer_text')->nullable()->after('selected_options');
            $table->json('matching_answers')->nullable()->after('answer_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropColumn(['question_type', 'matching_pairs', 'answer_key']);
        });

        Schema::table('cbt_student_answers', function (Blueprint $table) {
            $table->dropColumn(['selected_options', 'answer_text', 'matching_answers']);
        });
    }
};
