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
        Schema::create('graduation_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique(); // 6 = MI, 9 = MTs, 12 = MA
            $table->dateTime('announcement_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->text('announcement_text')->nullable(); // Text to display if graduated
            $table->text('non_graduation_text')->nullable(); // Text to display if not graduated / waiting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_settings');
    }
};
