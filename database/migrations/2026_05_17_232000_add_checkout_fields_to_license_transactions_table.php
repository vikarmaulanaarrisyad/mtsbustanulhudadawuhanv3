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
        Schema::table('license_transactions', function (Blueprint $table) {
            $table->string('module_key')->nullable()->after('module_name');
            $table->string('duration')->nullable()->after('amount');
            $table->string('transfer_proof')->nullable()->after('duration');
            $table->string('payment_method')->nullable()->after('transfer_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'module_key',
                'duration',
                'transfer_proof',
                'payment_method'
            ]);
        });
    }
};
