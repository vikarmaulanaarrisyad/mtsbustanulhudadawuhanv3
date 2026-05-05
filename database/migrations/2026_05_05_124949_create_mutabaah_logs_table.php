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
        Schema::create('mutabaah_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('date');
            
            // Shalat Fardhu
            $table->boolean('shubuh')->default(false);
            $table->boolean('zhuhur')->default(false);
            $table->boolean('ashar')->default(false);
            $table->boolean('maghrib')->default(false);
            $table->boolean('isya')->default(false);
            
            // Shalat Sunnah
            $table->boolean('dhuha')->default(false);
            $table->boolean('tahajud')->default(false);
            
            // Lainnya
            $table->string('puasa')->nullable(); // Senin-Kamis, Daud, Sunnah, Wajib
            $table->string('tadarus')->nullable(); // Keterangan bacaan
            
            // Validasi Orang Tua
            $table->boolean('is_validated_by_parent')->default(false);
            $table->text('parent_notes')->nullable();
            
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_logs');
    }
};
