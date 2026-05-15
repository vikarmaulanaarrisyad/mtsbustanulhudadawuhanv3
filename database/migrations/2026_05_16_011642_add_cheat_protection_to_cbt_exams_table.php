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
            $table->boolean('detect_tab_switch')->default(true)->after('is_active');
            $table->integer('max_violations')->default(5)->after('detect_tab_switch');
            $table->boolean('auto_finish_on_limit')->default(false)->after('max_violations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropColumn(['detect_tab_switch', 'max_violations', 'auto_finish_on_limit']);
        });
    }
};
