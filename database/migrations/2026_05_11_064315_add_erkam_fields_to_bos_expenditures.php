<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->after('amount');
            $table->date('noted_at')->nullable()->after('receipt_number');
            $table->date('realized_at')->nullable()->after('noted_at');
            $table->string('activity_code')->nullable()->after('category'); // For e-RKAM style codes
        });
    }

    public function down(): void
    {
        Schema::table('bos_expenditures', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'noted_at', 'realized_at', 'activity_code']);
        });
    }
};
