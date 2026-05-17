<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedInteger('workflow_price')->default(99000);
            $table->unsignedInteger('teachers_price')->default(99000);
            $table->unsignedInteger('cbt_price')->default(149000);
            $table->unsignedInteger('savings_price')->default(129000);
            $table->unsignedInteger('ppdb_price')->default(99000);
            $table->unsignedInteger('website_price')->default(79000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'workflow_price',
                'teachers_price',
                'cbt_price',
                'savings_price',
                'ppdb_price',
                'website_price'
            ]);
        });
    }
};
