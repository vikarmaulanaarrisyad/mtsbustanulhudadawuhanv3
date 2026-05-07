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
        $groups = PermissionGroup::all();
        $standardActions = ['view', 'show', 'create', 'edit', 'delete'];
        $extraActions = [
            'ppdb' => ['verify', 'export', 'print'],
            'student' => ['export', 'print'],
            'teacher' => ['export', 'print'],
            'grades' => ['export', 'print'],
            'teacher-attendance' => ['export'],
            'student-attendance' => ['export'],
        ];

        foreach ($groups as $group) {
            if (!$group->prefix) continue;

            // Generate standard permissions
            foreach ($standardActions as $action) {
                Permission::firstOrCreate(
                    ['name' => $group->prefix . '.' . $action, 'guard_name' => 'web'],
                    ['permission_group_id' => $group->id]
                );
            }

            // Generate extra permissions if defined
            if (isset($extraActions[$group->prefix])) {
                foreach ($extraActions[$group->prefix] as $action) {
                    Permission::firstOrCreate(
                        ['name' => $group->prefix . '.' . $action, 'guard_name' => 'web'],
                        ['permission_group_id' => $group->id]
                    );
                }
            }
        }

        // Special permissions that don't follow the pattern
        $specials = [
            ['name' => 'dashboard.admin', 'group' => 'Dashboard'],
            ['name' => 'dashboard.guru', 'group' => 'Dashboard'],
            ['name' => 'dashboard.siswa', 'group' => 'Dashboard'],
            ['name' => 'dashboard.ppdb', 'group' => 'Dashboard'],
        ];

        foreach ($specials as $special) {
            $group = PermissionGroup::where('name', $special['group'])->first();
            if ($group) {
                Permission::firstOrCreate(
                    ['name' => $special['name'], 'guard_name' => 'web'],
                    ['permission_group_id' => $group->id]
                );
            }
        }
    }
}
