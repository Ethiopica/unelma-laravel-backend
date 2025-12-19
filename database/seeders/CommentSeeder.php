<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'user_id' => 1,
                'blog_id' => 1,
                'content' => 'Great article! Laravel finally makes sense after reading this.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'blog_id' => 4,
                'content' => 'React is indeed dominating. I switched from Vue recently and the ecosystem is huge.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'blog_id' => 2,
                'content' => 'This was super helpful! I built my first REST API with Node.js today.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'blog_id' => 5,
                'content' => 'AI is changing everything. I already use AI tools daily in my workflow.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'blog_id' => 3,
                'content' => 'Very well explained. UI/UX basics are something every developer should know.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'blog_id' => 6,
                'content' => 'Debugging used to stress me out but these techniques really help.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'blog_id' => 1,
                'content' => 'Laravel’s Eloquent ORM is my favorite part. Amazing framework!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'blog_id' => 4,
                'content' => 'React is fast, but sometimes overwhelming for beginners. Good breakdown here.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'blog_id' => 2,
                'content' => 'Nice and simple explanation. API routing finally clicked!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'blog_id' => 5,
                'content' => 'Excited to see where AI takes software development in the next few years.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11,
                'blog_id' => 3,
                'content' => 'UX is always underrated. This article highlights important points clearly.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12,
                'blog_id' => 6,
                'content' => 'Love these debugging tips. Especially the part about breakpoints!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Comment::insert($comments);
    }
}
