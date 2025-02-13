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
        Comment::create([
            'post_id' => 1,
            'user_id' => 1,
            'body' => 'Comentario do Primeiro Post'
        ]);

        Comment::create([
            'post_id' => 1,
            'user_id' => 1,
            'parent_id' => 1,
            'body' => 'Comentario do Primeiro Comentario'
        ]);
    }
}
