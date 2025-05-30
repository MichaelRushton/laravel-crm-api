<?php

use App\Models\Customer;
use App\Models\User;

test('can delete customer', function () {

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.customers.destroy', Customer::factory()->create()))
        ->assertNoContent();

});

test('must be authenticated to delete customer', function () {

    $this->deleteJson(route('v1.customers.destroy', Customer::factory()->create()))
        ->assertUnauthorized();

});
