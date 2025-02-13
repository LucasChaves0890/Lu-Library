<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'A revolução dos bichos',
            'description' => 'Uma Distopia de George Orwell.',
            'gender' => 'Ficção',
            'price' => 29.99,
            'number_of_pages' => 510,
            'author_id' => 1,
        ]);

        Book::create([
            'title' => '1984',
            'description' => 'A dystopian novel by George Orwell.',
            'gender' => 'Distopia',
            'price' => 19.99,
            'number_of_pages' => 350,
            'author_id' => 1,
        ]);

        Book::create([
            'title' => 'No fundo do poço em París e Londres',
            'description' => 'Historias do George Orwell',
            'gender' => 'Descrição',
            'price' => 10,
            'number_of_pages' => 410,
            'author_id' => 1,
        ]);

        Book::create([
            'title' => 'Berserk',
            'description' => 'Mangá',
            'gender' => 'Fantasia',
            'price' => 35.89,
            'number_of_pages' => 360,
            'author_id' => 2,
        ]);

        Book::create([
            'title' => 'Dom Casmurro',
            'description' => 'Um romance narrado em primeira pessoa, um clássico da literatura brasileira.',
            'gender' => 'Romance',
            'price' => 29.89,
            'number_of_pages' => 420,
            'author_id' => 3,
        ]);
    }
}
