<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Super Admin: All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin: Also give all permissions as requested
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        // 3. Guru Permissions
        $guru = Role::firstOrCreate(['name' => 'Guru']);
        $guruPermissions = Permission::where(function($q) {
            $q->where('name', 'dashboard.guru')
              ->orWhere('name', 'like', 'teacher-attendance.%')
              ->orWhere('name', 'like', 'student-attendance.%')
              ->orWhere('name', 'subjects.view')
              ->orWhere('name', 'class-schedules.view')
              ->orWhere('name', 'posts.view');
        })->get();
        $guru->syncPermissions($guruPermissions);

        // 4. Siswa Permissions
        $siswa = Role::firstOrCreate(['name' => 'Siswa']);
        $siswaPermissions = Permission::where(function($q) {
            $q->where('name', 'dashboard.siswa')
              ->orWhere('name', 'posts.view')
              ->orWhere('name', 'student-attendance.view');
        })->get();
        $siswa->syncPermissions($siswaPermissions);

        // 5. PPDB (Calon Siswa) Permissions
        $ppdb = Role::firstOrCreate(['name' => 'ppdb']);
        $ppdbPermissions = Permission::where(function($q) {
            $q->where('name', 'dashboard.ppdb')
              ->orWhere('name', 'ppdb.view')
              ->orWhere('name', 'ppdb.create'); // Allow registration
        })->get();
        $ppdb->syncPermissions($ppdbPermissions);

        // 6. Petugas Verifikasi PPDB Permissions
        $petugasVerif = Role::firstOrCreate(['name' => 'Petugas Verifikasi PPDB']);
        $petugasVerifPermissions = Permission::where(function($q) {
            $q->where('name', 'dashboard.ppdb')
              ->orWhere('name', 'ppdb.view')
              ->orWhere('name', 'ppdb.verify');
        })->get();
        $petugasVerif->syncPermissions($petugasVerifPermissions);
    }
}
