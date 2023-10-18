<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashSale>
 */
class FlashSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'discount' => $this->faker->randomFloat(2, 5, 50), // Adjust the discount range as needed
            'start_time' => $this->faker->dateTimeThisMonth,
            'end_time' => $this->faker->dateTimeThisMonth,
        ];
    }
}
