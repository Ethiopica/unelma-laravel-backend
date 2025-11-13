<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Ava Stone',
                'email' => 'ava.stone@example.com',
                'message' => 'Hi, I wanted to ask about your web development services. Do you offer custom e-commerce solutions as well?',
                'ip_address' => '192.168.1.101',
                'is_read' => false,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Liam Cross',
                'email' => 'liam.cross@example.com',
                'message' => 'Hello, I am interested in your mobile app development services. Can you provide a quote for a new project?',
                'ip_address' => '192.168.1.102',
                'is_read' => false,
                'created_at' => now()->subWeeks(1),
                'updated_at' => now()->subWeeks(1),
            ],
            [
                'name' => 'Maya Rivers',
                'email' => 'maya.rivers@example.com',
                'message' => 'Good day, I visited your website and would like more information on UI/UX design packages you offer.',
                'ip_address' => '192.168.1.103',
                'is_read' => false,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'name' => 'Noah Blaze',
                'email' => 'noah.blaze@example.com',
                'message' => 'I have some questions regarding SEO optimization for my existing website. Can we schedule a call?',
                'ip_address' => '192.168.1.104',
                'is_read' => false,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Isla Hart',
                'email' => 'isla.hart@example.com',
                'message' => 'Hello team, I am looking for cloud hosting solutions and want to know your pricing details.',
                'ip_address' => '192.168.1.105',
                'is_read' => false,
                'created_at' => now()->subYears(1),
                'updated_at' => now()->subYears(1),
            ],
            [
                'name' => 'Ethan Vale',
                'email' => 'ethan.vale@example.com',
                'message' => 'Can you help me with digital marketing strategies for a small startup?',
                'ip_address' => '192.168.1.106',
                'is_read' => false,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subYears(2),
            ],
            [
                'name' => 'Sofia Crest',
                'email' => 'sofia.crest@example.com',
                'message' => 'I would like to know if you provide content writing services for blogs and social media posts.',
                'ip_address' => '192.168.1.107',
                'is_read' => false,
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Leo Knight',
                'email' => 'leo.knight@example.com',
                'message' => 'Hi, I need a quote for a full e-commerce platform with payment integration.',
                'ip_address' => '192.168.1.108',
                'is_read' => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name' => 'Nora Frost',
                'email' => 'nora.frost@example.com',
                'message' => 'Greetings, I am interested in IT consulting services. Can you provide an initial consultation?',
                'ip_address' => '192.168.1.109',
                'is_read' => false,
                'created_at' => now()->subMonths(18),
                'updated_at' => now()->subMonths(18),
            ],
            [
                'name' => 'Owen Hale',
                'email' => 'owen.hale@example.com',
                'message' => 'Hello, I want to learn more about cybersecurity services and data protection.',
                'ip_address' => '192.168.1.110',
                'is_read' => false,
                'created_at' => now()->subWeeks(3),
                'updated_at' => now()->subWeeks(3),
            ],
            [
                'name' => 'Chloe Lane',
                'email' => 'chloe.lane@example.com',
                'message' => 'Can you tell me more about your cloud hosting packages? Looking for enterprise-level services.',
                'ip_address' => '192.168.1.111',
                'is_read' => false,
                'created_at' => now()->subMonths(5),
                'updated_at' => now()->subMonths(5),
            ],
            [
                'name' => 'Mason York',
                'email' => 'mason.york@example.com',
                'message' => 'Hi, I need help with improving my website SEO rankings. What are your plans?',
                'ip_address' => '192.168.1.112',
                'is_read' => false,
                'created_at' => now()->subYears(2)->subMonths(1),
                'updated_at' => now()->subYears(2)->subMonths(1),
            ],
            [
                'name' => 'Ella Brooks',
                'email' => 'ella.brooks@example.com',
                'message' => 'I am looking for mobile app development for iOS and Android. Do you support both platforms?',
                'ip_address' => '192.168.1.113',
                'is_read' => false,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subMonths(1),
            ],
        ];

        ContactMessage::insert($messages);
    }
}
