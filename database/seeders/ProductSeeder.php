<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with adjustable DPI.',
                'price' => 25.99,
                'image' => 'mouse.jpg',
                'image_url' => 'https://example.com/images/mouse.jpg',
                'is_featured' => true,
                'is_active' => true,
                'order' => 1,
                'created_at' => '2025-11-01 09:00:00',
                'updated_at' => '2025-11-01 09:00:00',
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB backlit mechanical keyboard with blue switches.',
                'price' => 79.99,
                'image' => 'keyboard.jpg',
                'image_url' => 'https://example.com/images/keyboard.jpg',
                'is_featured' => true,
                'is_active' => true,
                'order' => 2,
                'created_at' => '2025-11-02 10:15:00',
                'updated_at' => '2025-11-02 10:15:00',
            ],
            [
                'name' => 'Gaming Headset',
                'description' => 'Surround sound headset with noise-cancelling mic.',
                'price' => 59.99,
                'image' => 'headset.jpg',
                'image_url' => 'https://example.com/images/headset.jpg',
                'is_featured' => false,
                'is_active' => true,
                'order' => 3,
                'created_at' => '2025-11-03 11:30:00',
                'updated_at' => '2025-11-03 11:30:00',
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader.',
                'price' => 34.50,
                'image' => 'usb_hub.jpg',
                'image_url' => 'https://example.com/images/usb_hub.jpg',
                'is_featured' => false,
                'is_active' => true,
                'order' => 4,
                'created_at' => '2025-11-04 12:45:00',
                'updated_at' => '2025-11-04 12:45:00',
            ],
            [
                'name' => 'Portable SSD 1TB',
                'description' => 'High-speed portable SSD, compact design.',
                'price' => 129.99,
                'image' => 'ssd.jpg',
                'image_url' => 'https://example.com/images/ssd.jpg',
                'is_featured' => true,
                'is_active' => true,
                'order' => 5,
                'created_at' => '2025-11-05 14:00:00',
                'updated_at' => '2025-11-05 14:00:00',
            ],
            [
                'name' => 'Smartphone Stand',
                'description' => 'Adjustable desktop smartphone stand.',
                'price' => 12.99,
                'image' => 'phone_stand.jpg',
                'image_url' => 'https://example.com/images/phone_stand.jpg',
                'is_featured' => false,
                'is_active' => true,
                'order' => 6,
                'created_at' => '2025-11-06 15:15:00',
                'updated_at' => '2025-11-06 15:15:00',
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with 10-hour battery.',
                'price' => 49.99,
                'image' => 'speaker.jpg',
                'image_url' => 'https://example.com/images/speaker.jpg',
                'is_featured' => true,
                'is_active' => true,
                'order' => 7,
                'created_at' => '2025-11-07 16:30:00',
                'updated_at' => '2025-11-07 16:30:00',
            ],
            [
                'name' => 'Webcam 1080p',
                'description' => 'Full HD webcam with built-in microphone.',
                'price' => 39.99,
                'image' => 'webcam.jpg',
                'image_url' => 'https://example.com/images/webcam.jpg',
                'is_featured' => false,
                'is_active' => true,
                'order' => 8,
                'created_at' => '2025-11-08 17:45:00',
                'updated_at' => '2025-11-08 17:45:00',
            ],
            [
                'name' => 'Laptop Sleeve 15-inch',
                'description' => 'Waterproof neoprene laptop sleeve.',
                'price' => 22.99,
                'image' => 'laptop_sleeve.jpg',
                'image_url' => 'https://example.com/images/laptop_sleeve.jpg',
                'is_featured' => false,
                'is_active' => true,
                'order' => 9,
                'created_at' => '2025-11-09 18:00:00',
                'updated_at' => '2025-11-09 18:00:00',
            ],
            [
                'name' => 'LED Desk Lamp',
                'description' => 'Adjustable LED desk lamp with touch control.',
                'price' => 29.99,
                'image' => 'desk_lamp.jpg',
                'image_url' => 'https://example.com/images/desk_lamp.jpg',
                'is_featured' => true,
                'is_active' => true,
                'order' => 10,
                'created_at' => '2025-11-10 19:15:00',
                'updated_at' => '2025-11-10 19:15:00',
            ],
        ];
        Product::insert($products);
    }
}
