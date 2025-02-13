<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;  

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = Faker::create();
        return [
            'name' => $this->faker->name,
            'sex' => $faker->randomElement(['masculino', 'feminino']),
            'description' => $this->faker->paragraph,
            'nacionality' => $this->faker->country
        ];
    }
}
