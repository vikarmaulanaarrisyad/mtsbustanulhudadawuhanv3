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
        Schema::table('settings', function (Blueprint $row) {
            $row->string('pwa_name')->default('Madrasah Digital MTs Bustanul Huda')->after('company_name');
            $row->string('pwa_short_name')->default('Madrasah')->after('pwa_name');
            $row->string('pwa_theme_color')->default('#10b981')->after('pwa_short_name');
            $row->string('pwa_background_color')->default('#ffffff')->after('pwa_theme_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $row) {
            $row->dropColumn(['pwa_name', 'pwa_short_name', 'pwa_theme_color', 'pwa_background_color']);
        });
    }
};
