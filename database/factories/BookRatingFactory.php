<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookRating>
 */
class BookRatingFactory extends Factory
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
            'rating' => $this->faker->randomFloat(1, 0.5, 5)
        ];
    }
}
