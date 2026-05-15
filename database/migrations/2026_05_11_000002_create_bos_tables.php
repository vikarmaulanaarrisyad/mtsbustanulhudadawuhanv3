<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bos_incomes', function (Blueprint $col) {
            $col->id();
            $col->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $col->enum('level', ['MI', 'MTs', 'MA']);
            $col->date('date');
            $col->decimal('amount', 15, 2);
            $col->string('source'); // e.g., BOS Reguler Tahap 1
            $col->text('description')->nullable();
            $col->timestamps();
        });

        Schema::create('bos_expenditures', function (Blueprint $col) {
            $col->id();
            $col->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $col->enum('level', ['MI', 'MTs', 'MA']);
            $col->date('date');
            $col->decimal('amount', 15, 2);
            $col->string('category'); // e.g., Honor, Sarpras, ATK
            $col->text('description')->nullable();
            $col->string('receiver')->nullable();
            $col->string('evidence_path')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bos_expenditures');
        Schema::dropIfExists('bos_incomes');
    }
};
