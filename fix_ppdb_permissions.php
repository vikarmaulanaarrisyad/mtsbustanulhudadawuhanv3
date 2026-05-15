<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Ensure permissions exist
$perms = [
    'ppdb.view',
    'ppdb.verify.berkas',
    'ppdb.verify.daftar_ulang',
    'admin.ppdb.committee.view' // New one maybe?
];

foreach ($perms as $p) {
    Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
}

$role = Role::where('name', 'Admin')->first();
if ($role) {
    $role->givePermissionTo($perms);
    echo "Permissions granted to Admin role.\n";
}

$super = Role::where('name', 'Super Admin')->first();
if ($super) {
    $super->givePermissionTo($perms);
    echo "Permissions granted to Super Admin role.\n";
}
