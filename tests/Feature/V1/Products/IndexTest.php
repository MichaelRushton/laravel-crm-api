<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list products', function () {

    $products = Product::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.products.index'))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($products) {

            $json->has('data', $products->count());

            foreach ($products->sortBy('name')->values() as $i => $product) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $product->id)
                    ->has('category', fn (AssertableJson $json) => $json->where('id', $product->category->id)
                        ->where('name', $product->category->name)
                    )
                    ->where('name', $product->name)
                    ->where('description', $product->description)
                );

            }

            $json->has('links')
                ->has('meta');

        });

});

test('can filter products', function ($column) {

    $product = Product::factory()->create();

    Product::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.products.index', ['filter' => [
            $column => $product->$column,
        ]]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->where('data.0.id', $product->id)
            ->etc()
        );

})
    ->with(['product_category_id', 'name']);

test('can change number of products per page', function () {

    Product::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.products.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});

test('must be authenticated to list products', function () {

    $this->getJson(route('v1.products.index'))
        ->assertUnauthorized();

});
