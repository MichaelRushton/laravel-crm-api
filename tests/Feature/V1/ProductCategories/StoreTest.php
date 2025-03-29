<?php

use App\Models\ProductCategory;
use App\Models\User;

test('requires name', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.product-categories.store'))
        ->assertJsonValidationErrors(['name']);

});

test('validates name', function ($name) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.product-categories.store'), [
            'name' => $name,
        ])
        ->assertJsonValidationErrors(['name']);

})
    ->with(['']);

test('can create product category', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.product-categories.store'), [
            'name' => $name = fake()->word(),
        ])
        ->assertCreated()
        ->assertExactJson([
            'data' => [
                'id' => ProductCategory::latest('id')->first()->id,
                'name' => $name,
            ],
        ]);

});

test('must be authenticated to create product category', function () {

    $this->postJson(route('v1.product-categories.store'))
        ->assertUnauthorized();

});
