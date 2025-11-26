<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Ava Stone',
                'email' => 'ava.stone@example.com',
                'password' => bcrypt('ava123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo1.jpg',
                'created_at' => '2025-11-01 10:12:45',
                'updated_at' => '2025-11-01 10:12:45',
            ],
            [
                'name' => 'Liam Cross',
                'email' => 'liam.cross@example.com',
                'password' => bcrypt('liam123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo2.jpg',

                'created_at' => '2025-11-02 09:31:10',
                'updated_at' => '2025-11-02 09:31:10',
            ],
            [
                'name' => 'Maya Rivers',
                'email' => 'maya.rivers@example.com',
                'password' => bcrypt('maya123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo3.jpg',

                'created_at' => '2025-11-03 14:45:21',
                'updated_at' => '2025-11-03 14:45:21',
            ],
            [
                'name' => 'Noah Blaze',
                'email' => 'noah.blaze@example.com',
                'password' => bcrypt('noah123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo4.jpg',

                'created_at' => '2025-11-04 08:20:54',
                'updated_at' => '2025-11-04 08:20:54',
            ],
            [
                'name' => 'Isla Hart',
                'email' => 'isla.hart@example.com',
                'password' => bcrypt('isla123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo2.jpg',

                'created_at' => '2025-11-05 16:12:03',
                'updated_at' => '2025-11-05 16:12:03',
            ],
            [
                'name' => 'Ethan Vale',
                'email' => 'ethan.vale@example.com',
                'password' => bcrypt('ethan123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo3.jpg',

                'created_at' => '2025-11-06 11:02:42',
                'updated_at' => '2025-11-06 11:02:42',
            ],
            [
                'name' => 'Sofia Crest',
                'email' => 'sofia.crest@example.com',
                'password' => bcrypt('sofia123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo4.jpg',

                'created_at' => '2025-11-07 19:48:55',
                'updated_at' => '2025-11-07 19:48:55',
            ],
            [
                'name' => 'Leo Knight',
                'email' => 'leo.knight@example.com',
                'password' => bcrypt('leo123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo1.jpg',

                'created_at' => '2025-11-08 07:29:30',
                'updated_at' => '2025-11-08 07:29:30',
            ],
            [
                'name' => 'Nora Frost',
                'email' => 'nora.frost@example.com',
                'password' => bcrypt('nora123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo2.jpg',

                'created_at' => '2025-11-09 12:40:11',
                'updated_at' => '2025-11-09 12:40:11',
            ],
            [
                'name' => 'Owen Hale',
                'email' => 'owen.hale@example.com',
                'password' => bcrypt('owen123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo3.jpg',

                'created_at' => '2025-11-10 18:25:59',
                'updated_at' => '2025-11-10 18:25:59',
            ],
            [
                'name' => 'Basu Pokharel',
                'email' => 'basudevpokharelfin@gmail.com',
                'password' => bcrypt('basu123'),
                'is_admin' => true,
                'role' => 'admin',
                "profile_picture" => 'profile_pictures/photo4.jpg',

                'created_at' => '2025-11-10 18:25:59',
                'updated_at' => '2025-11-10 18:25:59',
            ],
        ];
        User::insert($data);
    }
}
