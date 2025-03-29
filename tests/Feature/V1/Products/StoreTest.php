<?php

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;

test('requires category', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'))
        ->assertJsonValidationErrors(['product_category_id']);

});

test('validates category id', function ($product_category_id) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'), [
            'product_category_id' => $product_category_id,
        ])
        ->assertJsonValidationErrors(['product_category_id']);

})
    ->with([-1]);

test('requires name', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'))
        ->assertJsonValidationErrors(['name']);

});

test('validates name', function ($name) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'), [
            'name' => $name,
        ])
        ->assertJsonValidationErrors(['name']);

})
    ->with(['']);

test('requires description', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'))
        ->assertJsonValidationErrors(['description']);

});

test('validates description', function ($description) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'), [
            'description' => $description,
        ])
        ->assertJsonValidationErrors(['description']);

})
    ->with(['']);

test('can create product', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.products.store'), [
            'product_category_id' => ($category = ProductCategory::factory()->create())->id,
            'name' => $name = fake()->word(),
            'description' => $description = fake()->paragraph(),
        ])
        ->assertCreated()
        ->assertExactJson([
            'data' => [
                'id' => Product::latest('id')->first()->id,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'name' => $name,
                'description' => $description,
            ],
        ]);

});

test('must be authenticated to create product', function () {

    $this->postJson(route('v1.products.store'))
        ->assertUnauthorized();

});
