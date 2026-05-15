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
        Schema::create('ppdb_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_registrant_id')->constrained('ppdb_registrants')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // e.g., 'registration', 'verify_berkas', 'verify_daftar_ulang', 'selection'
            $table->text('description')->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('metadata')->nullable(); // For additional info like IP address, browser, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_logs');
    }
};
