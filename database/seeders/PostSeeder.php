<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Pastikan kamu sudah punya user_id (misal 1)
        $userId = User::inRandomOrder()->first()->id ?? 1;

        for ($i = 0; $i < 100000; $i++) {
            $title = $faker->sentence(rand(3, 7));
            Post::create([
                'post_title'           => $title,
                'post_slug'            => Str::slug($title) . '-' . Str::random(5),
                'post_type'            => $faker->randomElement(['article', 'news', 'tutorial']),
                'post_content'         => $faker->paragraphs(rand(3, 10), true),
                'post_image'           => 'post_image.jpg',
                'user_id'              => $userId,
                'post_status'          => $faker->randomElement(['publish', 'draft']),
                'post_visibility'      => $faker->randomElement(['public', 'private']),
                'post_comment_status'  => $faker->randomElement(['open', 'close']),
                'post_counter'         => $faker->numberBetween(0, 1000),
                'created_at'           => $faker->dateTimeBetween('-1 years', 'now'),
                'updated_at'           => now(),
            ]);
        }
    }
}
