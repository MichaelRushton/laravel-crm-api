<?php

use App\Models\Customer;
use App\Models\User;

test('must be authenticated to show customer', function () {

    $this->getJson(route('v1.customers.show', Customer::factory()->create()))
        ->assertUnauthorized();

});

test('can show customer', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.show', $customer))
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
            ],
        ]);

});
