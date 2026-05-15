<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_registrant_id')->constrained('ppdb_registrants')->cascadeOnDelete();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->unsignedInteger('unread_admin')->default(0);
            $table->unsignedInteger('unread_student')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ppdb_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_chat_room_id')->constrained('ppdb_chat_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('sender_type', ['student', 'admin', 'system'])->default('student');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_chat_messages');
        Schema::dropIfExists('ppdb_chat_rooms');
    }
};
