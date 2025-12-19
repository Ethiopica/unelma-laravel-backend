<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'content' => '<h1>Welcome to Unelma</h1><p>Your premier cloud backend solution.</p>',
                'meta_description' => 'Welcome to Unelma - Cloud Backend Server',
                'featured_image' => 'profile_pictures/photo4.jpg',
                'meta_keywords' => 'unelma, cloud, backend, home',
                'is_published' => true,
                'order' => 1,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About Us</h1><p>Learn more about our company and mission.</p>',
                'meta_description' => 'Learn about Unelma and our mission',
                'featured_image' => 'profile_pictures/photo3.jpg',

                'meta_keywords' => 'about, company, mission, unelma',
                'is_published' => true,
                'order' => 2,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'content' => '<h1>Our Services</h1><p>Discover the services we offer.</p>',
                'meta_description' => 'Professional services offered by Unelma',
                'featured_image' => 'profile_pictures/photo2.jpg',

                'meta_keywords' => 'services, solutions, cloud services',
                'is_published' => true,
                'order' => 3,
            ],
            [
                'title' => 'Products',
                'slug' => 'products',
                'content' => '<h1>Our Products</h1><p>Browse our product catalog.</p>',
                'meta_description' => 'Explore Unelma products and solutions',
                'featured_image' => 'profile_pictures/photo1.jpg',

                'meta_keywords' => 'products, catalog, solutions',
                'is_published' => true,
                'order' => 4,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Contact Us</h1><p>Get in touch with our team.</p>',
                'meta_description' => 'Contact Unelma for inquiries and support',
                'featured_image' => 'profile_pictures/photo2.jpg',

                'meta_keywords' => 'contact, support, inquiries',
                'is_published' => true,
                'order' => 5,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Default pages created successfully!');
        $this->command->info('Pages: Home, About, Services, Products, Contact Us');
    }
}
