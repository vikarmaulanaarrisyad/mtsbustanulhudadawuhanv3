<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('admin_note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'payment_proof', 'admin_note']);
        });
    }
};
