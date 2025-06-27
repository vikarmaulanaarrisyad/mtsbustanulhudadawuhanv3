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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title');
            $table->string('post_slug');
            $table->string('post_type');
            $table->longText('post_content');
            $table->string('post_image')->default('post_image.jpg');
            $table->unsignedBigInteger('user_id');
            $table->enum('post_status', ['publish', 'draft'])->default('draft');
            $table->enum('post_visibility', ['public', 'private'])->default('public');
            $table->enum('post_comment_status', ['open', 'close'])->default('close');
            $table->integer('post_counter')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
