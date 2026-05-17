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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('owner_bank_name')->default('BANK TRANSFER BCA');
            $table->string('owner_bank_account')->default('8392-1209-9021');
            $table->string('owner_bank_holder')->default('PT MARDIK DIGITAL INDONESIA');
            $table->string('owner_qris_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['owner_bank_name', 'owner_bank_account', 'owner_bank_holder', 'owner_qris_path']);
        });
    }
};
