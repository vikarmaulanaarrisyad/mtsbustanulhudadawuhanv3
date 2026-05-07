<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;

$role = Role::where('name', 'Guru')->first();
if ($role) {
    echo "Role: Guru\n";
    echo "Permissions:\n";
    foreach ($role->permissions as $p) {
        echo "- " . $p->name . "\n";
    }
} else {
    echo "Role Guru not found.\n";
}
