<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FullProjectPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'Dashboard' => [
                'dashboard.admin',
                'dashboard.guru',
                'dashboard.siswa',
                'dashboard.ppdb',
            ],
            'User Management' => [
                'user.view', 'user.create', 'user.edit', 'user.delete',
                'role.view', 'role.create', 'role.edit', 'role.delete',
                'permission.view', 'permission-group.view',
            ],
            'Academic' => [
                'academic-year.view', 'academic-year.create', 'academic-year.edit', 'academic-year.delete',
                'class-group.view', 'class-group.create', 'class-group.edit', 'class-group.delete',
                'subject.view', 'subject.create', 'subject.edit', 'subject.delete',
                'schedule.view', 'schedule.create', 'schedule.edit', 'schedule.delete',
                'study-period.view', 'study-period.create', 'study-period.edit', 'study-period.delete',
            ],
            'Teachers' => [
                'teacher.view', 'teacher.create', 'teacher.edit', 'teacher.delete',
                'position.view', 'position.create', 'position.edit', 'position.delete',
                'teacher.permit.view', 'teacher.permit.verify',
            ],
            'Students' => [
                'student.view', 'student.create', 'student.edit', 'student.delete',
                'student.card.view', 'student.placement.view', 'student.promotion.view', 'student.graduation.view',
                'student.status.view', 'student.permit.view', 'student.permit.verify',
            ],
            'Attendance' => [
                'attendance.setting.view', 'holiday.view', 'holiday.create', 'holiday.edit', 'holiday.delete',
                'teacher.attendance.view', 'teacher.attendance.manage', 'teacher.attendance.report',
                'student.attendance.view', 'student.attendance.scan', 'student.attendance.manage',
            ],
            'PPDB' => [
                'ppdb.view', 'ppdb.create', 'ppdb.edit', 'ppdb.delete', 'ppdb.verify',
                'ppdb.payment.view', 'ppdb.scanner.view',
            ],
            'Correspondence' => [
                'mail.setting.view',
                'mail.outgoing.view', 'mail.outgoing.create', 'mail.outgoing.edit', 'mail.outgoing.delete',
                'mail.certificate.view', 'mail.certificate.create',
                'mail.statement.view', 'mail.statement.create',
                'mail.transfer.view', 'mail.transfer.create',
                'mail.meeting.view', 'mail.meeting.create',
                'mail.duty-letter.view', 'mail.duty-letter.create',
            ],
            'Behavior & Points' => [
                'behavior-log.view', 'behavior-log.create', 'behavior-log.delete',
            ],
            'Grades' => [
                'grade.view', 'grade.manage', 'grade.setting.view',
            ],
            'Financial' => [
                'payroll.view', 'payroll.manage', 'payment-item.view',
            ],
            'Blog & Content' => [
                'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'tags.view', 'tags.create', 'tags.edit', 'tags.delete',
                'image-sliders.view', 'image-sliders.create', 'image-sliders.edit', 'image-sliders.delete',
                'albums.view', 'albums.create', 'albums.edit', 'albums.delete',
                'quotes.view', 'quotes.create', 'quotes.edit', 'quotes.delete',
                'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
                'opening-speech.view', 'opening-speech.edit',
            ],
            'System' => [
                'setting.view', 'setting.edit',
                'backup.view', 'backup.manage',
                'log.view', 'pwa.view',
            ],
        ];

        foreach ($modules as $groupName => $perms) {
            // Create group if not exists
            $group = PermissionGroup::firstOrCreate(['name' => $groupName]);

            foreach ($perms as $permName) {
                Permission::updateOrCreate(
                    ['name' => $permName, 'guard_name' => 'web'],
                    ['permission_group_id' => $group->id]
                );
            }
        }

        // Assign all permissions to Super Admin role
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Assign most permissions to Admin role (except system sensitive ones)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::whereNotIn('name', [
            'role.delete', 'permission.delete', 'backup.manage', 'setting.edit'
        ])->get());

        // Assign specific permissions to Guru role
        $guruRole = Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
        $guruRole->syncPermissions([
            'dashboard.guru',
            'schedule.view',
            'student.view',
            'behavior-log.create',
            'behavior-log.view',
            'student.attendance.scan',
            'teacher.attendance.view',
            'grade.manage',
        ]);

        // Assign specific permissions to Siswa role
        $siswaRole = Role::firstOrCreate(['name' => 'Siswa', 'guard_name' => 'web']);
        $siswaRole->syncPermissions([
            'dashboard.siswa',
            'behavior-log.view',
        ]);

        // Assign specific permissions to PPDB role
        $ppdbRole = Role::firstOrCreate(['name' => 'ppdb', 'guard_name' => 'web']);
        $ppdbRole->syncPermissions([
            'dashboard.ppdb',
            'ppdb.view',
            'ppdb.create',
            'ppdb.edit',
        ]);
    }
}
