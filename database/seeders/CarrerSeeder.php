<?php

namespace Database\Seeders;

use App\Models\Carrer;
use Illuminate\Database\Seeder;

class CarrerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carrers = [
            [
                'name' => 'Software Engineer',
                'description' => 'Design, develop, and maintain software applications for various platforms and industries.',
                'created_at' => '2025-11-01 09:00:00',
                'updated_at' => '2025-11-01 09:00:00',
            ],
            [
                'name' => 'UI/UX Designer',
                'description' => 'Create user-friendly interfaces and engaging user experiences for websites and mobile apps.',
                'created_at' => '2025-11-02 10:15:00',
                'updated_at' => '2025-11-02 10:15:00',
            ],
            [
                'name' => 'Digital Marketing Specialist',
                'description' => 'Plan and execute digital marketing campaigns, including social media, SEO, and content marketing.',
                'created_at' => '2025-11-03 11:30:00',
                'updated_at' => '2025-11-03 11:30:00',
            ],
            [
                'name' => 'Cloud Solutions Architect',
                'description' => 'Design and implement scalable cloud infrastructure and solutions for organizations.',
                'created_at' => '2025-11-04 12:45:00',
                'updated_at' => '2025-11-04 12:45:00',
            ],
            [
                'name' => 'Cybersecurity Analyst',
                'description' => 'Monitor and protect systems and networks from cyber threats and ensure data security.',
                'created_at' => '2025-11-05 14:00:00',
                'updated_at' => '2025-11-05 14:00:00',
            ],
        ];

        Carrer::insert($carrers);
    }
}
