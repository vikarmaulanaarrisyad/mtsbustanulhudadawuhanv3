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
        Schema::create('bos_expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kate')->nullable();
            $table->string('kategori')->nullable();
            $table->string('kode_jenis')->nullable();
            $table->string('jenis')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bos_expense_types');
    }
};
