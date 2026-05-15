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
            $table->enum('type', ['regular', 'remedial', 'susulan'])->default('regular')->after('cbt_bank_id');
            $table->enum('exam_mode', ['all_class', 'selected_students'])->default('all_class')->after('type');
            $table->foreignId('parent_exam_id')->nullable()->after('exam_mode')->constrained('cbt_exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropForeign(['parent_exam_id']);
            $table->dropColumn(['type', 'exam_mode', 'parent_exam_id']);
        });
    }
};
