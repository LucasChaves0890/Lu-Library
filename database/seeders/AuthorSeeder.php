<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::create([
            'name' => 'George Orwell',
            'sex' => 'masculino',
            'description' => 'George Orwell foi o pseudônimo que ',
            'nacionality' => 'Britânico',
        ]);

        Author::create([
            'name' => 'Kentaro Miura',
            'sex' => 'masculino',
            'description' => 'Mangaká japonês',
            'nacionality' => 'Japonês'
        ]);

        Author::create([
            'name' => 'Machado de assis',
            'sex' => 'masculino',
            'description' => 'Escritor brasileiro',
            'nacionality' => 'Brasileiro'
        ]);
    }
}
