<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mail_settings', function (Blueprint $table) {
            $table->string('default_signer_name')->nullable();
            $table->string('default_signer_position')->nullable();
            $table->string('default_signer_nip', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mail_settings', function (Blueprint $table) {
            $table->dropColumn(['default_signer_name', 'default_signer_position', 'default_signer_nip']);
        });
    }
};
