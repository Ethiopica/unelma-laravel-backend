<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            PageSeeder::class,
            ContactMessageSeeder::class,
            careerSeeder::class,
            ProductSeeder::class,
            BlogSeeder::class,
            ServiceSeeder::class,
            CommentSeeder::class,

        ]);
    }
}
