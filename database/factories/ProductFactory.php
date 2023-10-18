<?php

namespace Database\Factories;

use App\Models\Product; // Adjust the namespace if needed
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'inventory' => $this->faker->numberBetween(0, 100),
        ];
    }
}
