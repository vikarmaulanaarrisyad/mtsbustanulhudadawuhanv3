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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('headmaster_name')->nullable()->after('company_name');
            $table->string('headmaster_nip')->nullable()->after('headmaster_name');
            $table->string('path_signature')->nullable()->after('path_image_footer');
        });

        // Initialize with owner name as default headmaster
        $setting = \App\Models\Setting::first();
        if ($setting) {
            $setting->update([
                'headmaster_name' => $setting->owner_name,
                'headmaster_nip' => '-'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['headmaster_name', 'headmaster_nip', 'path_signature']);
        });
    }
};
