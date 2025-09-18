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

            // Residences
            ['name' => 'residences.view', 'group' => 'Residences'],
            ['name' => 'residences.create', 'group' => 'Residences'],
            ['name' => 'residences.update', 'group' => 'Residences'],
            ['name' => 'residences.delete', 'group' => 'Residences'],

            // Category
            ['name' => 'categories.view', 'group' => 'Category'],
            ['name' => 'categories.create', 'group' => 'Category'],
            ['name' => 'categories.update', 'group' => 'Category'],
            ['name' => 'categories.delete', 'group' => 'Category'],

            // Post
            ['name' => 'posts.view', 'group' => 'Post'],
            ['name' => 'posts.create', 'group' => 'Post'],
            ['name' => 'posts.update', 'group' => 'Post'],
            ['name' => 'posts.delete', 'group' => 'Post'],

            // Tags
            ['name' => 'tags.view', 'group' => 'Tags'],
            ['name' => 'tags.create', 'group' => 'Tags'],
            ['name' => 'tags.update', 'group' => 'Tags'],
            ['name' => 'tags.delete', 'group' => 'Tags'],

            // Image Sliders
            ['name' => 'image-sliders.view', 'group' => 'Image Sliders'],
            ['name' => 'image-sliders.create', 'group' => 'Image Sliders'],
            ['name' => 'image-sliders.update', 'group' => 'Image Sliders'],

            // Admission Quotas
            ['name' => 'admission-quotas.view', 'group' => 'Admission Quotas'],
            ['name' => 'admission-quotas.create', 'group' => 'Admission Quotas'],
            ['name' => 'admission-quotas.update', 'group' => 'Admission Quotas'],
            ['name' => 'admission-quotas.delete', 'group' => 'Admission Quotas'],

            // Admission Types
            ['name' => 'admission-types.view', 'group' => 'Admission Types'],
            ['name' => 'admission-types.create', 'group' => 'Admission Types'],
            ['name' => 'admission-types.update', 'group' => 'Admission Types'],
            ['name' => 'admission-types.delete', 'group' => 'Admission Types'],

            // Admission Phases
            ['name' => 'admission-phases.view', 'group' => 'Admission Phases'],
            ['name' => 'admission-phases.create', 'group' => 'Admission Phases'],
            ['name' => 'admission-phases.update', 'group' => 'Admission Phases'],
            ['name' => 'admission-phases.delete', 'group' => 'Admission Phases'],

            // Student Admission
            ['name' => 'student-admissions.view', 'group' => 'Student Admission'],
            ['name' => 'student-admissions.create', 'group' => 'Student Admission'],
            ['name' => 'student-admissions.update', 'group' => 'Student Admission'],
            ['name' => 'student-admissions.delete', 'group' => 'Student Admission'],

            // Student Status
            ['name' => 'student-status.view', 'group' => 'Student Status'],
            ['name' => 'student-status.create', 'group' => 'Student Status'],
            ['name' => 'student-status.update', 'group' => 'Student Status'],
            ['name' => 'student-status.delete', 'group' => 'Student Status'],

            // Educations
            ['name' => 'educations.view', 'group' => 'Educations'],
            ['name' => 'educations.create', 'group' => 'Educations'],
            ['name' => 'educations.update', 'group' => 'Educations'],
            ['name' => 'educations.delete', 'group' => 'Educations'],

            // Opening Speech
            ['name' => 'opening-speech.view', 'group' => 'Opening Speech'],
            ['name' => 'opening-speech.create', 'group' => 'Opening Speech'],
            ['name' => 'opening-speech.update', 'group' => 'Opening Speech'],
            ['name' => 'opening-speech.delete', 'group' => 'Opening Speech'],

            // Opening Speech
            ['name' => 'quotes.view', 'group' => 'Quotes'],
            ['name' => 'quotes.create', 'group' => 'Quotes'],
            ['name' => 'quotes.update', 'group' => 'Quotes'],
            ['name' => 'quotes.delete', 'group' => 'Quotes'],
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
