<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        $categories = ProductCategory::factory(5)->create();

        Product::factory(50)
            ->recycle($categories)
            ->create();

    }
}
