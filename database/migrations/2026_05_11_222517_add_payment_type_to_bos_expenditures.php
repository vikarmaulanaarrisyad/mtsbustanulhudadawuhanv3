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
            $table->string('item_payment_type')->nullable()->after('item_unit');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn('item_payment_type');
        });
    }
};
