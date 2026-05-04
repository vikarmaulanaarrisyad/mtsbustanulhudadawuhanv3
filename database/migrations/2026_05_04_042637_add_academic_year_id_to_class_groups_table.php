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
        Schema::table('class_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_year_id')->nullable()->after('sub_class_group');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
        });

        // Assign existing classes to the current academic year if available
        $currentAY = DB::table('academic_years')->where('current_semester', true)->first();
        if ($currentAY) {
            DB::table('class_groups')->whereNull('academic_year_id')->update(['academic_year_id' => $currentAY->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
