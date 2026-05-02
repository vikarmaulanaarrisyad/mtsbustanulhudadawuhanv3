<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "ROLES:\n";
foreach (Role::all() as $role) {
    echo "- {$role->name} (guard: {$role->guard_name})\n";
}

echo "\nPERMISSIONS (first 20):\n";
foreach (Permission::take(20)->get() as $p) {
    echo "- {$p->name} (guard: {$p->guard_name})\n";
}

$ppdb = Role::where('name', 'ppdb')->first();
if ($ppdb) {
    echo "\nPPDB Permissions:\n";
    foreach ($ppdb->permissions as $p) {
        echo "- {$p->name}\n";
    }
} else {
    echo "\nRole 'ppdb' NOT FOUND in DB!\n";
}

$siswa = Role::where('name', 'Siswa')->first();
if ($siswa) {
    echo "\nSiswa Permissions:\n";
    foreach ($siswa->permissions as $p) {
        echo "- {$p->name}\n";
    }
} else {
    echo "\nRole 'Siswa' NOT FOUND in DB!\n";
}
