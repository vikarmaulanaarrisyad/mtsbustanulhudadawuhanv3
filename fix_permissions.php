<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

try {
    $roles = Role::whereIn('name', ['Super Admin', 'Admin'])->get();
    $permissions = Permission::where('name', 'like', 'ppdb%')->get();
    
    foreach($roles as $role) {
        $role->givePermissionTo($permissions);
    }
    
    echo "Hak akses PPDB berhasil diberikan ke Super Admin & Admin.\n";
} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
}
