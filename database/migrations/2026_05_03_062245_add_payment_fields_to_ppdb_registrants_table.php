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
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            if (!Schema::hasColumn('ppdb_registrants', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_proof');
            }
            if (!Schema::hasColumn('ppdb_registrants', 'payment_amount')) {
                $table->decimal('payment_amount', 15, 2)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('ppdb_registrants', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->after('payment_amount');
            }
            if (!Schema::hasColumn('ppdb_registrants', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('midtrans_order_id');
            }
            if (!Schema::hasColumn('ppdb_registrants', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('midtrans_snap_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrants', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_amount',
                'midtrans_order_id',
                'midtrans_snap_token',
                'payment_status'
            ]);
        });
    }
};
