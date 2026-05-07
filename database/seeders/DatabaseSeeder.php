<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Sapu bersih SEMUA file dan folder di storage public
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $allDirectories = $disk->allDirectories();
        $allFiles = $disk->allFiles();

        // Hapus semua folder
        foreach ($disk->directories() as $directory) {
            $disk->deleteDirectory($directory);
        }

        // Hapus semua file yang tersisa di root public
        foreach ($disk->files() as $file) {
            if ($file !== '.gitignore') {
                $disk->delete($file);
            }
        }

        $this->call([
            PermissionGroupTableSeeder::class,
            PermissionTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserTableSeeder::class,
            SettingSeeder::class,
            SemesterSeeder::class,
            StudentStatusSeeder::class,
            FeatureTestingSeeder::class,
            GradeManagementSeeder::class,
            PositionSeeder::class,
        ]);
    }
}
