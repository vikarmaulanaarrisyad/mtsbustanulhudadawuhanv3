<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$guruRole = Role::where('name', 'Guru')->first();
if ($guruRole) {
    echo "Guru Permissions:\n";
    foreach ($guruRole->permissions as $p) {
        echo "- " . $p->name . "\n";
    }
} else {
    echo "Guru Role not found.\n";
}
