<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class NewModulesPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'PPDB' => [
                'ppdb.view',
                'ppdb.create',
                'ppdb.edit',
                'ppdb.delete',
                'ppdb.verify',
            ],
            'Akademik' => [
                'subjects.view',
                'subjects.create',
                'subjects.edit',
                'subjects.delete',
                'class-schedules.view',
                'class-schedules.create',
                'class-schedules.edit',
                'class-schedules.delete',
                'study-periods.view',
                'study-periods.create',
                'study-periods.edit',
                'study-periods.delete',
            ],
            'Absensi' => [
                'student-attendance.view',
                'student-attendance.scan',
                'teacher-attendance.view',
                'attendance-settings.view',
                'attendance-settings.update',
            ],
        ];

        foreach ($modules as $groupName => $permissions) {
            $group = PermissionGroup::firstOrCreate(['name' => $groupName]);
            
            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                    'permission_group_id' => $group->id
                ]);
            }
        }
    }
}
