<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class FullGranularPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'Guru' => 'teacher',
            'Siswa' => 'student',
            'Alumni' => 'alumni',
            'Kelas' => 'class-group',
            'Mata Pelajaran' => 'subjects',
            'Jadwal' => 'class-schedules',
            'Nilai Siswa' => 'grades',
            'Absensi Guru' => 'teacher-attendance',
            'Absensi Siswa' => 'student-attendance',
            'Layanan Surat' => 'mail',
            'Pengaturan' => 'setting',
            'Berita/Artikel' => 'posts',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $groupName => $prefix) {
            $group = PermissionGroup::firstOrCreate(['name' => $groupName]);

            foreach ($actions as $action) {
                $permissionName = $prefix . '.' . $action;
                
                $perm = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
                if ($perm) {
                    $perm->update(['permission_group_id' => $group->id]);
                } else {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                        'permission_group_id' => $group->id
                    ]);
                }
            }
        }

        // Special for PPDB (already handled but ensuring group)
        $ppdbGroup = PermissionGroup::firstOrCreate(['name' => 'PPDB Online']);
        $ppdbActions = ['view', 'create', 'edit', 'delete', 'verify'];
        foreach ($ppdbActions as $action) {
            $permissionName = 'ppdb.' . $action;
            $perm = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
            if ($perm) {
                $perm->update(['permission_group_id' => $ppdbGroup->id]);
            } else {
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                    'permission_group_id' => $ppdbGroup->id
                ]);
            }
        }
    }
}
