<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class NewPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Website & Konten' => [
                'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'tags.view', 'tags.create', 'tags.edit', 'tags.delete',
                'pages.view', 'pages.create', 'pages.edit', 'pages.delete',
                'albums.view', 'albums.create', 'albums.edit', 'albums.delete',
                'menus.view',
            ],
            'Akademik Lanjutan' => [
                'subjects.view', 'subjects.create', 'subjects.edit', 'subjects.delete',
                'class-schedules.view', 'class-schedules.create', 'class-schedules.edit', 'class-schedules.delete',
                'study-periods.view', 'study-periods.create', 'study-periods.edit', 'study-periods.delete',
                'promotions.view', 'promotions.create',
                'graduations.view', 'graduations.create',
            ],
            'Presensi' => [
                'teacher-attendance.view', 'teacher-attendance.manage',
                'student-attendance.view', 'student-attendance.scan', 'student-attendance.cards',
                'attendance-settings.view', 'attendance-settings.manage',
                'holidays.view', 'holidays.create', 'holidays.edit', 'holidays.delete',
            ],
            'Pengumuman' => [
                'announcements.view', 'announcements.create', 'announcements.edit', 'announcements.delete',
            ],
            'Persuratan' => [
                'mail-settings.view', 'mail-settings.manage',
                'outgoing-mails.view', 'outgoing-mails.create', 'outgoing-mails.edit', 'outgoing-mails.delete',
                'student-certificates.view', 'student-certificates.create',
                'student-active-statements.view', 'student-active-statements.create',
                'student-transfers.view', 'student-transfers.create',
                'school-meetings.view', 'school-meetings.create',
                'duty-letters.view', 'duty-letters.create',
            ],
            'PPDB Advanced' => [
                'ppdb.view', 'ppdb.verify', 'ppdb.delete',
                'student-admissions.view', 'student-admissions.manage',
            ],
        ];

        foreach ($data as $groupName => $permissions) {
            $group = PermissionGroup::firstOrCreate(['name' => $groupName]);

            foreach ($permissions as $permissionName) {
                Permission::updateOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web'
                    ],
                    [
                        'permission_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
