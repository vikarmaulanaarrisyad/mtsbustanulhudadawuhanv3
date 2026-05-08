<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$affected1 = DB::table('students')
    ->where('skl_number', 'like', '%//%')
    ->update(['skl_number' => DB::raw("REPLACE(skl_number, '//', '/')")]);

$affected2 = DB::table('students')
    ->where('registration_number', 'like', '%//%')
    ->update(['registration_number' => DB::raw("REPLACE(registration_number, '//', '/')")]);

echo "Fixed $affected1 SKL numbers and $affected2 SKNR numbers.\n";
