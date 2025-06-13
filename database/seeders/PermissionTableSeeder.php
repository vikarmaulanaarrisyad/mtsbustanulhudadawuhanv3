<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'group' => 'Dashboard'],

            // Konfigurasi
            ['name' => 'config.view', 'group' => 'Konfigurasi'],

            // User
            ['name' => 'user.view', 'group' => 'User'],
            ['name' => 'user.create', 'group' => 'User'],
            ['name' => 'user.show', 'group' => 'User'],
            ['name' => 'user.edit', 'group' => 'User'],
            ['name' => 'user.update', 'group' => 'User'],
            ['name' => 'user.delete', 'group' => 'User'],

            // Role
            ['name' => 'role.view', 'group' => 'Role'],
            ['name' => 'role.create', 'group' => 'Role'],
            ['name' => 'role.edit', 'group' => 'Role'],
            ['name' => 'role.show', 'group' => 'Role'],
            ['name' => 'role.update', 'group' => 'Role'],
            ['name' => 'role.delete', 'group' => 'Role'],

            // Permission
            ['name' => 'permission.view', 'group' => 'Permission'],
            ['name' => 'permission.create', 'group' => 'Permission'],
            ['name' => 'permission.show', 'group' => 'Permission'],
            ['name' => 'permission.edit', 'group' => 'Permission'],
            ['name' => 'permission.update', 'group' => 'Permission'],
            ['name' => 'permission.delete', 'group' => 'Permission'],

            // Group Permission
            ['name' => 'permission-group.view', 'group' => 'Group Permission'],
            ['name' => 'permission-group.create', 'group' => 'Group Permission'],
            ['name' => 'permission-group.show', 'group' => 'Group Permission'],
            ['name' => 'permission-group.edit', 'group' => 'Group Permission'],
            ['name' => 'permission-group.update', 'group' => 'Group Permission'],
            ['name' => 'permission-group.delete', 'group' => 'Group Permission'],

            // Pengaturan
            ['name' => 'setting.view', 'group' => 'Pengaturan'],

            // Academic Year
            ['name' => 'academic-year.view', 'group' => 'Academic Year'],
            ['name' => 'academic-year.create', 'group' => 'Academic Year'],
            ['name' => 'academic-year.update', 'group' => 'Academic Year'],
            ['name' => 'academic-year.delete', 'group' => 'Academic Year'],

            // PPDB
            ['name' => 'admision.view', 'group' => 'Admission'],
            ['name' => 'admision.create', 'group' => 'Admission'],
            ['name' => 'admision.update', 'group' => 'Admission'],
            ['name' => 'admision.delete', 'group' => 'Admission'],

            // Class Group
            ['name' => 'class-group.view', 'group' => 'Class Group'],
            ['name' => 'class-group.create', 'group' => 'Class Group'],
            ['name' => 'class-group.update', 'group' => 'Class Group'],
            ['name' => 'class-group.delete', 'group' => 'Class Group'],

            // Class Group
            ['name' => 'transportation.view', 'group' => 'Transportation'],
            ['name' => 'transportation.create', 'group' => 'Transportation'],
            ['name' => 'transportation.update', 'group' => 'Transportation'],
            ['name' => 'transportation.delete', 'group' => 'Transportation'],

            // Monthly Income
            ['name' => 'monthly-income.view', 'group' => 'Monthly Income'],
            ['name' => 'monthly-income.create', 'group' => 'Monthly Income'],
            ['name' => 'monthly-income.update', 'group' => 'Monthly Income'],
            ['name' => 'monthly-income.delete', 'group' => 'Monthly Income'],

            // Monthly Income
            ['name' => 'student-status.view', 'group' => 'Student Status'],
            ['name' => 'student-status.create', 'group' => 'Student Status'],
            ['name' => 'student-status.update', 'group' => 'Student Status'],
            ['name' => 'student-status.delete', 'group' => 'Student Status'],
        ];

        foreach ($permissions as $value) {
            $group = PermissionGroup::where('name', $value['group'])->first();

            if ($group) {
                Permission::firstOrCreate(
                    ['name' => $value['name']],
                    ['permission_group_id' => $group->id]
                );
            }
        }
    }
}
