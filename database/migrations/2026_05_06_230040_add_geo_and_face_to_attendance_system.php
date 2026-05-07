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
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('work_days');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('radius')->default(100)->after('longitude'); // in meters
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('check_in_lat', 10, 8)->nullable()->after('check_in');
            $table->decimal('check_in_lng', 11, 8)->nullable()->after('check_in_lat');
            $table->string('image_in')->nullable()->after('check_in_lng');
            
            $table->decimal('check_out_lat', 10, 8)->nullable()->after('check_out');
            $table->decimal('check_out_lng', 11, 8)->nullable()->after('check_out_lat');
            $table->string('image_out')->nullable()->after('check_out_lng');
        });

        Schema::create('teacher_face_descriptors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->json('descriptors');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_face_descriptors');
        
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['check_in_lat', 'check_in_lng', 'image_in', 'check_out_lat', 'check_out_lng', 'image_out']);
        });

        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius']);
        });
    }
};
