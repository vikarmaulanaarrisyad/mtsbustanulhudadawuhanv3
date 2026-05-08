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
        Schema::create('performance_assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_assessment_id')->constrained('performance_assessments')->onDelete('cascade');
            $table->foreignId('performance_indicator_id')->constrained('performance_indicators')->onDelete('cascade');
            $table->integer('score')->comment('1-5 scale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_assessment_details');
    }
};
