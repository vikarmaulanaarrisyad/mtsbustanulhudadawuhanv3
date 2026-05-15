<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_savings', function (Blueprint $バランス) {
            $バランス->id();
            $バランス->foreignId('student_id')->constrained()->onDelete('cascade');
            $バランス->decimal('balance', 15, 2)->default(0);
            $バランス->timestamp('last_transaction_at')->nullable();
            $バランス->timestamps();
        });

        Schema::create('student_saving_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_saving_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['debit', 'credit']); // debit = deposit (+), credit = withdrawal (-)
            $table->decimal('amount', 15, 2);
            $table->decimal('current_balance', 15, 2); // balance after this transaction
            $table->string('description')->nullable();
            $table->string('reference_no')->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_saving_transactions');
        Schema::dropIfExists('student_savings');
    }
};
