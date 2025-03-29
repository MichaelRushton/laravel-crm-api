<?php

use App\Models\ProductCategory;
use App\Models\User;

test('validates name', function ($name) {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.product-categories.update', ProductCategory::factory()->create()), [
            'name' => $name,
        ])
        ->assertJsonValidationErrors(['name']);

})
    ->with(['']);

test('can update product category', function () {

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.product-categories.update', $product_category = ProductCategory::factory()->create()), [
            'name' => $name = fake()->word(),
        ])
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $product_category->id,
                'name' => $name,
            ],
        ]);

});

test('must be authenticated to update product category', function () {

    $this->patchJson(route('v1.product-categories.update', ProductCategory::factory()->create()))
        ->assertUnauthorized();

});
