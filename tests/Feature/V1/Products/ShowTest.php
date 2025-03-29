<?php

use App\Models\Product;
use App\Models\User;

test('can show product', function () {

    $product = Product::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.products.show', $product))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $product->id,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'name' => $product->name,
                'description' => $product->description,
            ],
        ]);

});

test('must be authenticated to show product', function () {

    $this->getJson(route('v1.products.show', Product::factory()->create()))
        ->assertUnauthorized();

});
