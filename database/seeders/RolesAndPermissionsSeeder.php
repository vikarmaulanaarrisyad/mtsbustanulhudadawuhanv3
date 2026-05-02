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

        // 2. Admin Permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $adminPermissions = Permission::where(function($q) {
            $q->where('name', 'dashboard.view')
              ->orWhere('name', 'like', 'ppdb.%')
              ->orWhere('name', 'like', 'subjects.%')
              ->orWhere('name', 'like', 'class-schedules.%')
              ->orWhere('name', 'like', 'study-periods.%')
              ->orWhere('name', 'like', 'student-attendance.%')
              ->orWhere('name', 'like', 'teacher-attendance.%')
              ->orWhere('name', 'like', 'attendance-settings.%')
              ->orWhere('name', 'like', 'academic-year.%')
              ->orWhere('name', 'like', 'class-group.%')
              ->orWhere('name', 'like', 'student-status.%')
              ->orWhere('name', 'like', 'residences.%')
              ->orWhere('name', 'like', 'transportation.%')
              ->orWhere('name', 'like', 'educations.%')
              ->orWhere('name', 'like', 'posts.%')
              ->orWhere('name', 'like', 'categories.%')
              ->orWhere('name', 'like', 'tags.%')
              ->orWhere('name', 'like', 'image-sliders.%')
              ->orWhere('name', 'like', 'opening-speech.%')
              ->orWhere('name', 'like', 'quotes.%')
              ->orWhere('name', 'menus.view')
              ->orWhere('name', 'like', 'user.%') // Admin can manage users
              ->orWhere('name', 'like', 'students.%') // If exists
              ->orWhere('name', 'like', 'teachers.%') // If exists
              ->orWhere('name', 'like', 'admission.%')
              ->orWhere('name', 'like', 'student-admissions.%')
              ->orWhere('name', 'like', 'mail.%')
              ->orWhere('name', 'like', 'outgoing-mails.%')
              ->orWhere('name', 'like', 'student-certificates.%')
              ->orWhere('name', 'like', 'active-statements.%')
              ->orWhere('name', 'like', 'student-transfers.%')
              ->orWhere('name', 'like', 'school-meetings.%')
              ->orWhere('name', 'like', 'duty-letters.%')
              ->orWhere('name', 'like', 'promotions.%')
              ->orWhere('name', 'like', 'graduations.%');
        })->get();
        $admin->syncPermissions($adminPermissions);

        // 3. Guru Permissions
        $guru = Role::firstOrCreate(['name' => 'Guru']);
        $guruPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'teacher-attendance.view',
            'student-attendance.view',
            'student-attendance.scan',
            'subjects.view',
            'class-schedules.view',
            'study-periods.view',
            'posts.view',
            'categories.view',
            'tags.view',
            'academic-year.view',
            'class-group.view',
        ])->get();
        $guru->syncPermissions($guruPermissions);

        // 4. Siswa Permissions
        $siswa = Role::firstOrCreate(['name' => 'Siswa']);
        $siswaPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'posts.view',
            'categories.view',
            'tags.view',
        ])->get();
        $siswa->syncPermissions($siswaPermissions);

        // 5. PPDB (Calon Siswa) Permissions
        $ppdb = Role::firstOrCreate(['name' => 'ppdb']);
        $ppdbPermissions = Permission::whereIn('name', [
            'dashboard.view',
        ])->get();
        $ppdb->syncPermissions($ppdbPermissions);
    }
}
