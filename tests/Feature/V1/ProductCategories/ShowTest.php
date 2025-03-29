<?php

use App\Models\ProductCategory;
use App\Models\User;

test('can show product category', function () {

    $product_category = ProductCategory::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.product-categories.show', $product_category))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $product_category->id,
                'name' => $product_category->name,
            ],
        ]);

});

test('must be authenticated to show product category', function () {

    $this->getJson(route('v1.product-categories.show', ProductCategory::factory()->create()))
        ->assertUnauthorized();

});
