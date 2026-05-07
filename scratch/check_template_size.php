<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Exports\CbtTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

try {
    $export = new CbtTemplateExport('Test Bank');
    $path = 'storage/app/public/test_template.xlsx';
    Excel::store($export, 'test_template.xlsx', 'public');
    echo "File generated at: " . storage_path('app/public/test_template.xlsx') . "\n";
    echo "Size: " . filesize(storage_path('app/public/test_template.xlsx')) . " bytes\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
