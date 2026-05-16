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
            $table->integer('wave')->nullable()->change();
            $table->integer('session')->nullable()->change();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->integer('cbt_wave')->nullable()->change();
            $table->integer('cbt_session')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->integer('wave')->nullable(false)->change();
            $table->integer('session')->nullable(false)->change();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->integer('cbt_wave')->nullable(false)->change();
            $table->integer('cbt_session')->nullable(false)->change();
        });
    }
};
