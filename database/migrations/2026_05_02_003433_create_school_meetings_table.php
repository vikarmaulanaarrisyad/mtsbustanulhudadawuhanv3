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
        Schema::create('school_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_number')->unique();
            $table->date('mail_date');
            $table->string('meeting_subject');
            $table->string('recipient_description'); // e.g. "Bapak/Ibu Wali Murid Kelas IX"
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->string('meeting_place');
            $table->text('meeting_agenda');
            $table->string('signer_name')->nullable();
            $table->string('signer_nip')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_meetings');
    }
};
