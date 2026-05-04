<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\AcademicYear;

// Set ID 2 (Ganjil 2026/2027) to Active
AcademicYear::where('id', '>', 0)->update(['current_semester' => false]);
AcademicYear::where('id', 2)->update(['current_semester' => true]);

echo "Successfully activated Academic Year ID 2 (Ganjil 2026/2027).\n";
