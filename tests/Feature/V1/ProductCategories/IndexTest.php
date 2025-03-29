<?php

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list product categories', function () {

    $product_categories = ProductCategory::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.product-categories.index'))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($product_categories) {

            $json->has('data', $product_categories->count());

            foreach ($product_categories->sortBy('name')->values() as $i => $product_category) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $product_category->id)
                    ->where('name', $product_category->name)
                );

            }

            $json->has('links')
                ->has('meta');

        });

});

test('can filter product categories', function ($column) {

    $product_category = ProductCategory::factory()->create();

    ProductCategory::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.product-categories.index', ['filter' => [
            $column => $product_category->$column,
        ]]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->where('data.0.id', $product_category->id)
            ->etc()
        );

})
    ->with(['name']);

test('can change number of product categories per page', function () {

    ProductCategory::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.product-categories.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});

test('must be authenticated to list product categories', function () {

    $this->getJson(route('v1.product-categories.index'))
        ->assertUnauthorized();

});
