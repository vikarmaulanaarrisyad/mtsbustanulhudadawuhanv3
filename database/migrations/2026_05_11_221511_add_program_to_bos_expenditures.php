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
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->string('program_code')->nullable()->after('academic_year_id');
            $table->string('program_name')->nullable()->after('program_code');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn(['program_code', 'program_name']);
        });
    }
};
