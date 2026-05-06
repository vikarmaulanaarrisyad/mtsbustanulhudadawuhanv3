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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('name');
            $table->string('nuptk', 20)->nullable()->after('nik');
            $table->enum('gender', ['L', 'P'])->nullable()->after('nuptk');
            $table->string('place_of_birth')->nullable()->after('gender');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
            $table->text('address')->nullable()->after('date_of_birth');
            $table->string('phone')->nullable()->after('address');
            $table->string('employment_status')->nullable()->after('phone')->comment('PNS, GTY, PTY, Honorer, dll');
            $table->string('education')->nullable()->after('employment_status');
            $table->string('major')->nullable()->after('education');
            $table->string('university')->nullable()->after('major');
            $table->date('start_date')->nullable()->after('university')->comment('TMT');
            $table->boolean('certification_status')->default(false)->after('start_date');
            $table->string('bank_name')->nullable()->after('certification_status');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            $table->decimal('base_salary', 15, 2)->default(0)->after('bank_account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'nuptk', 'gender', 'place_of_birth', 'date_of_birth',
                'address', 'phone', 'employment_status', 'education', 'major',
                'university', 'start_date', 'certification_status', 'bank_name',
                'bank_account_number', 'bank_account_name', 'base_salary'
            ]);
        });
    }
};
