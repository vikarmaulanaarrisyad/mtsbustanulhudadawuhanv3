<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_quotas', function (Blueprint $table) {
            $table->unsignedBigInteger('admission_phase_id')->nullable()->after('academic_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_quotas', function (Blueprint $table) {
            $table->dropColumn('admission_phase_id');
        });
    }
};
