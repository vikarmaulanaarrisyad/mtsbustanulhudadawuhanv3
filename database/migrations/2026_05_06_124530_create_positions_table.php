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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->nullable()->unique()->comment('Kode Singkatan, misal: KAMAD, WAKA-KUR');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_signer')->default(false)->comment('Apakah jabatan ini berhak menandatangani dokumen resmi');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
