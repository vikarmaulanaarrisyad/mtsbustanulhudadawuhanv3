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
        Schema::table('students', function (Blueprint $table) {
            $table->integer('current_class_level')->nullable()->after('student_class_group_id')->index();
        });

        // Populate existing data
        $students = \App\Models\Student::with('classGroup')->get();
        foreach ($students as $student) {
            if ($student->classGroup) {
                $student->update(['current_class_level' => $student->classGroup->class_level]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('current_class_level');
        });
    }
};
