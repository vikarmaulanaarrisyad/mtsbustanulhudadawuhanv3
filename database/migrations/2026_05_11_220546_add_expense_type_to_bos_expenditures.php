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
            $table->string('expense_category')->nullable()->after('amount');
            $table->string('expense_type')->nullable()->after('expense_category');
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn(['expense_category', 'expense_type']);
        });
    }
};
