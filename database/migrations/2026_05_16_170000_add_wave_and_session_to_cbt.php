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
            $table->integer('wave')->default(1)->after('exam_date');
            $table->integer('session')->default(1)->after('wave');
            $table->string('room')->nullable()->after('session');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->integer('cbt_wave')->default(1)->after('qr_token');
            $table->integer('cbt_session')->default(1)->after('cbt_wave');
            $table->string('cbt_room')->nullable()->after('cbt_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropColumn(['wave', 'session', 'room']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['cbt_wave', 'cbt_session', 'cbt_room']);
        });
    }
};
