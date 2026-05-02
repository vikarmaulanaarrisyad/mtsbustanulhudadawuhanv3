<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtracurricularAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Extracurricular::create([
            'name' => 'Pramuka',
            'slug' => 'pramuka',
            'icon' => 'fa-campground',
            'description' => 'Membentuk karakter disiplin dan mandiri.',
        ]);

        \App\Models\Extracurricular::create([
            'name' => 'Hadrah',
            'slug' => 'hadrah',
            'icon' => 'fa-drum',
            'description' => 'Seni musik islami yang menyejukkan hati.',
        ]);

        \App\Models\Extracurricular::create([
            'name' => 'IT Club',
            'slug' => 'it-club',
            'icon' => 'fa-laptop-code',
            'description' => 'Belajar pemrograman dan desain grafis.',
        ]);

        \App\Models\Achievement::create([
            'title' => 'Juara 1 Lomba MTQ',
            'student_name' => 'Ahmad Fauzi',
            'rank' => '1',
            'year' => '2025',
        ]);

        \App\Models\Achievement::create([
            'title' => 'Juara 2 Kaligrafi',
            'student_name' => 'Siti Aminah',
            'rank' => '2',
            'year' => '2024',
        ]);
    }
}
