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
        Schema::create('spp_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('class_level'); // 7, 8, 9
            $table->decimal('amount', 15, 2);
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('spp_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['Unpaid', 'Paid', 'Partial'])->default('Unpaid');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('spp_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spp_billing_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method')->default('Cash'); // Cash, Transfer
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_payments');
        Schema::dropIfExists('spp_billings');
        Schema::dropIfExists('spp_settings');
    }
};
