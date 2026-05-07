<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddUserShowPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Forget cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure "User" group exists
        $group = PermissionGroup::firstOrCreate(['name' => 'User']);

        // Create the permission for web guard
        $permission = Permission::firstOrCreate(
            ['name' => 'user.show', 'guard_name' => 'web'],
            ['permission_group_id' => $group->id]
        );

        // Assign to Super Admin and Admin roles
        $superAdmin = Role::where('name', 'Super Admin')->where('guard_name', 'web')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permission);
        }

        $admin = Role::where('name', 'Admin')->where('guard_name', 'web')->first();
        if ($admin) {
            $admin->givePermissionTo($permission);
        }
    }
}
