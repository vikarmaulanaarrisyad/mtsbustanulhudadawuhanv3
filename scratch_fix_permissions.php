<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

$role = Role::where('name', 'Super Admin')->first();
if ($role) {
    $role->givePermissionTo(['ppdb.verify.berkas', 'ppdb.verify.daftar_ulang']);
}
app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
echo "Done\n";
