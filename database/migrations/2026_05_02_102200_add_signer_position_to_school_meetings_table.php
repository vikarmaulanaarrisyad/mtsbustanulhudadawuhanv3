<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_meetings', function (Blueprint $table) {
            $table->string('signer_position')->nullable()->after('signer_name');
        });
    }

    public function down(): void
    {
        Schema::table('school_meetings', function (Blueprint $table) {
            $table->dropColumn('signer_position');
        });
    }
};
