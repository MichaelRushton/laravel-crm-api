<?php

use App\Models\Product;
use App\Models\User;

test('can delete product', function () {

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.products.destroy', Product::factory()->create()))
        ->assertNoContent();

});

test('must be authenticated to delete product', function () {

    $this->deleteJson(route('v1.products.destroy', Product::factory()->create()))
        ->assertUnauthorized();

});
