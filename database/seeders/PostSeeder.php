<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'user_id' => 1,
            'book_id' => 1,
            'body' => 'Primeiro Post'
        ]);

        Post::create([
            'user_id' => 2,
            'book_id' => 2,
            'body' => 'Segundo Post'
        ]);
    }
}
