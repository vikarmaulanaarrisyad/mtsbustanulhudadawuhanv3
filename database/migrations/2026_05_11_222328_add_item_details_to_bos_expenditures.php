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
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->string('item_name')->nullable()->after('deskripsi');
            $table->string('item_code')->nullable()->after('item_name');
            $table->string('item_specification')->nullable()->after('item_code');
            $table->string('item_unit')->nullable()->after('item_specification');
            $table->decimal('item_price_1', 15, 2)->nullable()->after('item_unit');
            $table->decimal('item_price_2', 15, 2)->nullable()->after('item_price_1');
            $table->decimal('item_price_3', 15, 2)->nullable()->after('item_price_2');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn([
                'item_name', 'item_code', 'item_specification', 'item_unit', 
                'item_price_1', 'item_price_2', 'item_price_3'
            ]);
        });
    }
};
