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
        Schema::table('welcome_messages', function (Blueprint $table) {
            $table->string('path_image')->default('default.png');
            $table->string('name')->default('-');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('welcome_messages', function (Blueprint $table) {
            $table->dropColumn([
                'path_image',
                'name'
            ]);
        });
    }
};
