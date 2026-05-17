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
            if (!Schema::hasColumn('settings', 'gemini_tokens_this_month')) {
                $table->unsignedInteger('gemini_tokens_this_month')->default(0);
            }
            if (!Schema::hasColumn('settings', 'groq_tokens_this_month')) {
                $table->unsignedInteger('groq_tokens_this_month')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'gemini_tokens_this_month')) {
                $table->dropColumn('gemini_tokens_this_month');
            }
            if (Schema::hasColumn('settings', 'groq_tokens_this_month')) {
                $table->dropColumn('groq_tokens_this_month');
            }
        });
    }
};
