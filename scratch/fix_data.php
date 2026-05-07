<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClassGroup;

echo "Fixing Class Groups...\n";
$classes = ClassGroup::all();
foreach ($classes as $c) {
    if (empty($c->class_group)) {
        $c->class_group = 'VII';
        $c->sub_class_group = 'A';
        $c->group_name = 'VII A'; // Adding this just in case even if not in migration
        $c->save();
        echo "Fixed Class ID: {$c->id}\n";
    }
}
echo "Done.\n";
