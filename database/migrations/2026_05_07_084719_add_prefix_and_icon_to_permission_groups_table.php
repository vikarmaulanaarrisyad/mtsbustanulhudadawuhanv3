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
        Schema::table('permission_groups', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('name');
            $table->string('icon')->nullable()->after('prefix');
            $table->boolean('has_verify')->default(false)->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_groups', function (Blueprint $table) {
            $table->dropColumn(['prefix', 'icon', 'has_verify']);
        });
    }
};
