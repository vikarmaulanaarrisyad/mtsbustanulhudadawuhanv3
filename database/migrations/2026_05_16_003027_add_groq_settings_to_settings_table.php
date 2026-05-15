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
            $table->string('ai_provider')->default('gemini')->after('gemini_model');
            $table->string('groq_api_key')->nullable()->after('ai_provider');
            $table->string('groq_model')->default('llama3-8b-8192')->after('groq_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ai_provider', 'groq_api_key', 'groq_model']);
        });
    }
};
