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
            $table->boolean('is_teachers_pro_active')->default(0);
            $table->boolean('is_cbt_pro_active')->default(0);
            $table->boolean('is_savings_pro_active')->default(0);
            $table->boolean('is_ppdb_pro_active')->default(0);
            $table->boolean('is_website_pro_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'is_teachers_pro_active',
                'is_cbt_pro_active',
                'is_savings_pro_active',
                'is_ppdb_pro_active',
                'is_website_pro_active'
            ]);
        });
    }
};
