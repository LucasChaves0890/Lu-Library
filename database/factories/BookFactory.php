<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $author = Author::factory()->create();

        return [
            "title"=> $this->faker->title,
            "description" => $this->faker->paragraph,
            "gender" =>  $this->faker->name,
            "price" => $this->faker->randomFloat(2,0,0),
            "author_id" => $author->id,
            "number_of_pages" => $this->faker->numberBetween(100, 560)
        ];
    }
}
