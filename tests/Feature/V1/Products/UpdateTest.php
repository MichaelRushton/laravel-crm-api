<?php

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;

test('validates category id', function ($product_category_id) {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.products.update', Product::factory()->create()), [
            'product_category_id' => $product_category_id,
        ])
        ->assertJsonValidationErrors(['product_category_id']);

})
    ->with([-1]);

test('validates name', function ($name) {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.products.update', Product::factory()->create()), [
            'name' => $name,
        ])
        ->assertJsonValidationErrors(['name']);

})
    ->with(['']);

test('validates description', function ($description) {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.products.update', Product::factory()->create()), [
            'description' => $description,
        ])
        ->assertJsonValidationErrors(['description']);

})
    ->with(['']);

test('can update product', function () {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.products.update', $product = Product::factory()->create()), [
            'product_category_id' => ($category = ProductCategory::factory()->create())->id,
            'name' => $name = fake()->word(),
            'description' => $description = fake()->paragraph(),
        ])
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $product->id,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'name' => $name,
                'description' => $description,
            ],
        ]);

});

test('must be authenticated to update product', function () {

    $this->patchJson(route('v1.products.update', Product::factory()->create()))
        ->assertUnauthorized();

});
