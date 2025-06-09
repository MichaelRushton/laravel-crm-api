<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(mt_rand(1, 3), true)),
            'product_category_id' => ProductCategory::factory(),
            'description' => $this->faker->paragraphs(asText: true),
        ];
    }
}
