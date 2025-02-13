<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class BookToReadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        return [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'pages_read' => $book->number_of_pages
        ]; 
    }
}
