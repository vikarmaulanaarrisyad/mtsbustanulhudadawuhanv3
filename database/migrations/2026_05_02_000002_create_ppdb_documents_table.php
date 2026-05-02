<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ppdb_registrant_id')
                ->constrained('ppdb_registrants')
                ->cascadeOnDelete();

            $table->string('document_name');
            $table->string('document_type', 50);
            $table->string('file_path');
            $table->boolean('is_verified')->default(false);
            $table->string('verification_note')->nullable();

            $table->timestamps();

            $table->index('ppdb_registrant_id');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_documents');
    }
};
