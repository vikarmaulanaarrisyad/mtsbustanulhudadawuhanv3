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
        Schema::create('ppdb_payment_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('amount', 15, 2);
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_payment_items');
    }
};
