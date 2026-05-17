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
        Schema::create('license_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->string('module_name');
            $table->integer('amount');
            $table->string('status')->default('SUCCESS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_transactions');
    }
};
