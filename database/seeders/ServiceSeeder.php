<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Web Development',
                'description' => 'Building responsive and modern websites with latest technologies.',
                'icon' => 'fa-code',
                'image' => 'profile_pictures/photo2.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
                'created_at' => '2025-11-01 09:00:00',
                'updated_at' => '2025-11-01 09:00:00',
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Creating cross-platform and native mobile applications.',
                'icon' => 'fa-mobile',
                'image' => 'profile_pictures/photo3.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
                'created_at' => '2025-11-02 10:15:00',
                'updated_at' => '2025-11-02 10:15:00',
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Designing intuitive interfaces and user experiences.',
                'icon' => 'fa-pencil-ruler',
                'image' => 'profile_pictures/photo1.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => false,
                'order' => 3,
                'created_at' => '2025-11-03 11:30:00',
                'updated_at' => '2025-11-03 11:30:00',
            ],
            [
                'name' => 'SEO Optimization',
                'description' => 'Improving website ranking and visibility on search engines.',
                'icon' => 'fa-search',
                'image' => 'profile_pictures/photo2.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 4,
                'created_at' => '2025-11-04 12:45:00',
                'updated_at' => '2025-11-04 12:45:00',
            ],
            [
                'name' => 'Cloud Hosting',
                'description' => 'Reliable and scalable cloud hosting services.',
                'icon' => 'fa-cloud',
                'image' => 'profile_pictures/photo3.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => false,
                'order' => 5,
                'created_at' => '2025-11-05 14:00:00',
                'updated_at' => '2025-11-05 14:00:00',
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Promoting brands through digital channels effectively.',
                'icon' => 'fa-bullhorn',
                'image' => 'profile_pictures/photo4.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 6,
                'created_at' => '2025-11-06 15:15:00',
                'updated_at' => '2025-11-06 15:15:00',
            ],
            [
                'name' => 'Content Writing',
                'description' => 'High-quality content creation for websites and blogs.',
                'icon' => 'fa-pen',
                'image' => 'profile_pictures/photo1.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => false,
                'order' => 7,
                'created_at' => '2025-11-07 16:30:00',
                'updated_at' => '2025-11-07 16:30:00',
            ],
            [
                'name' => 'E-commerce Solutions',
                'description' => 'Developing scalable e-commerce platforms for businesses.',
                'icon' => 'fa-shopping-cart',
                'image' => 'profile_pictures/photo2.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 8,
                'created_at' => '2025-11-08 17:45:00',
                'updated_at' => '2025-11-08 17:45:00',
            ],
            [
                'name' => 'IT Consulting',
                'description' => 'Providing expert IT consulting and strategy services.',
                'icon' => 'fa-lightbulb',
                'image' => 'profile_pictures/photo3.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => false,
                'order' => 9,
                'created_at' => '2025-11-09 18:00:00',
                'updated_at' => '2025-11-09 18:00:00',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Protecting systems and data from cyber threats.',
                'icon' => 'fa-shield-alt',
                'image' => 'profile_pictures/photo4.jpg',
                'image_url' => null,
                'is_active' => true,
                'is_featured' => true,
                'order' => 10,
                'created_at' => '2025-11-10 19:15:00',
                'updated_at' => '2025-11-10 19:15:00',
            ],
        ];

        Service::insert($services);
    }
}
