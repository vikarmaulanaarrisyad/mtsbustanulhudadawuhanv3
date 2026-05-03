<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->decimal('average_score', 8, 2)->nullable()->after('status');
            $table->decimal('distance_km', 8, 2)->nullable()->after('average_score');
            $table->decimal('selection_score', 10, 2)->nullable()->after('distance_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->dropColumn(['average_score', 'distance_km', 'selection_score']);
        });
    }
};
