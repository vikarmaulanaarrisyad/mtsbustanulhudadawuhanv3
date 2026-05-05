<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('pwa_icon')->nullable()->after('pwa_background_color');
            $table->string('pwa_version')->default('1.0.0')->after('pwa_icon');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['pwa_icon', 'pwa_version']);
        });
    }
};
