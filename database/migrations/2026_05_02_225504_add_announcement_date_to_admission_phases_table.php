<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_phases', function (Blueprint $table) {
            $table->date('announcement_date')->nullable()->after('phase_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_phases', function (Blueprint $table) {
            $table->dropColumn('announcement_date');
        });
    }
};
