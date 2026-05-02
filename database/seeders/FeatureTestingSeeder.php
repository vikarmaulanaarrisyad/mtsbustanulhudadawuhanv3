<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\ClassGroup;
use App\Models\ClassSchedule;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\StudyPeriod;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FeatureTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setting
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'MTs Bustanul Huda',
                'owner_name' => 'Kepala Madrasah',
                'email' => 'info@mtsbh.sch.id',
                'phone' => '08123456789',
                'address' => 'Dawuhan, Indonesia',
                'path_image' => 'default.jpg',
            ]
        );

        // 2. Academic Year
        $academicYear = AcademicYear::updateOrCreate(
            ['academic_year' => '2025/2026'],
            [
                'semester_id' => 1,
                'current_semester' => 1,
            ]
        );

        // 3. Student Status
        $statusAktif = \App\Models\StudentStatus::updateOrCreate(['student_status_name' => 'Aktif']);

        // 4. Study Periods
        $periods = [];
        for ($i = 1; $i <= 8; $i++) {
            $periods[] = StudyPeriod::updateOrCreate(
                ['period_number' => $i],
                [
                    'start_time' => sprintf('%02d:00', 7 + $i),
                    'end_time' => sprintf('%02d:45', 7 + $i),
                    'is_break' => false
                ]
            );
        }

        // 5. Teachers & Users
        $teachers = [];
        $teacherData = [
            ['name' => 'Budi Santoso, S.Pd', 'nip' => '198001012005011001', 'email' => 'budi@guru.com'],
            ['name' => 'Siti Aminah, M.Pd', 'nip' => '198505052010012001', 'email' => 'siti@guru.com'],
            ['name' => 'Ahmad Fauzi, S.Si', 'nip' => '199012122015011002', 'email' => 'ahmad@guru.com'],
        ];

        foreach ($teacherData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => strstr($data['email'], '@', true),
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('Guru');

            $teachers[] = Teacher::updateOrCreate(
                ['nip' => $data['nip']],
                [
                    'name' => $data['name'],
                    'user_id' => $user->id,
                    'position' => 'Guru Mapel',
                ]
            );
        }

        // 6. Subjects
        $subjectNames = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PAI', 'PJOK'];
        $subjects = [];
        foreach ($subjectNames as $name) {
            $subjects[] = Subject::updateOrCreate(
                ['name' => $name],
                ['code' => strtoupper(substr($name, 0, 3)) . rand(100, 999)]
            );
        }

        // 7. Class Groups
        $classes = [];
        $levels = ['VII', 'VIII', 'IX'];
        $subs = ['A', 'B', 'C'];
        foreach ($levels as $level) {
            foreach ($subs as $sub) {
                $classes[] = ClassGroup::updateOrCreate(
                    ['class_group' => $level, 'sub_class_group' => $sub],
                    [
                        'teacher_id' => $teachers[array_rand($teachers)]->id,
                    ]
                );
            }
        }

        // 8. Students
        foreach ($classes as $class) {
            for ($i = 1; $i <= 5; $i++) {
                $name = "Siswa " . $class->class_group . $class->sub_class_group . " " . $i;
                Student::updateOrCreate(
                    ['nama_lengkap' => $name],
                    [
                        'nis' => rand(10000, 99999),
                        'nisn' => rand(10000000, 99999999),
                        'student_class_group_id' => $class->id,
                        'academic_year_id' => $academicYear->id,
                        'student_status_id' => $statusAktif->id,
                        'is_active' => true,
                        'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                        'tempat_lahir' => 'Dawuhan',
                        'tanggal_lahir' => '2012-01-01',
                    ]
                );
            }
        }

        // 9. Class Schedules
        foreach ($classes as $class) {
            // Monday only for testing
            for ($i = 0; $i < 4; $i++) {
                ClassSchedule::updateOrCreate(
                    [
                        'class_group_id' => $class->id,
                        'day' => 1, // Senin
                        'study_period_id' => $periods[$i]->id,
                    ],
                    [
                        'subject_id' => $subjects[array_rand($subjects)]->id,
                        'teacher_id' => $teachers[array_rand($teachers)]->id,
                        'academic_year_id' => $academicYear->id,
                        'start_time' => $periods[$i]->start_time,
                        'end_time' => $periods[$i]->end_time,
                    ]
                );
            }
        }

        // 10. Announcements
        $admin = User::role('Admin')->first() ?? User::role('Super Admin')->first();
        if ($admin) {
            Announcement::updateOrCreate(
                ['title' => 'Selamat Datang di Sistem Baru'],
                [
                    'content' => '<p>Ini adalah pengumuman testing untuk semua user.</p>',
                    'type' => 'Umum',
                    'user_id' => $admin->id,
                    'is_active' => true,
                ]
            );
            Announcement::updateOrCreate(
                ['title' => 'Rapat Guru Senin Depan'],
                [
                    'content' => '<p>Mohon kehadiran semua guru pada rapat hari senin.</p>',
                    'type' => 'Guru',
                    'user_id' => $admin->id,
                    'is_active' => true,
                ]
            );
        }

        // 11. Blog
        $category = Category::updateOrCreate(
            ['category_name' => 'Berita'],
            ['category_slug' => 'berita', 'category_description' => 'Berita Madrasah']
        );
        $tag = Tag::updateOrCreate(
            ['tag_name' => 'Madrasah'],
            ['tag_slug' => 'madrasah']
        );

        for ($i = 1; $i <= 3; $i++) {
            $post = Post::updateOrCreate(
                ['post_title' => 'Berita Testing Ke-' . $i],
                [
                    'post_slug' => Str::slug('Berita Testing Ke-' . $i),
                    'post_content' => 'Konten berita testing yang panjang untuk simulasi fitur blog.',
                    'post_type' => 'post',
                    'user_id' => $admin ? $admin->id : 1,
                    'post_status' => 'publish',
                ]
            );
            $post->categories()->sync([$category->id]);
            $post->tags()->sync([$tag->id]);
        }
    }
}
