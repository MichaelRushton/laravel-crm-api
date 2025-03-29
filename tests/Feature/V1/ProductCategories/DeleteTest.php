<?php

use App\Models\ProductCategory;
use App\Models\User;

test('can delete product category', function () {

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.product-categories.destroy', ProductCategory::factory()->create()))
        ->assertNoContent();

});

test('must be authenticated to delete product category', function () {

    $this->deleteJson(route('v1.product-categories.destroy', ProductCategory::factory()->create()))
        ->assertUnauthorized();

});
