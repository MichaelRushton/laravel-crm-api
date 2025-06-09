<?php

use App\Models\Customer;
use App\Models\User;

test('requires first name', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'))
        ->assertJsonValidationErrors(['first_name']);

});

test('validates first name', function ($first_name) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'first_name' => $first_name,
        ])
        ->assertJsonValidationErrors(['first_name']);

})
    ->with(['']);

test('requires last name', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'))
        ->assertJsonValidationErrors(['last_name']);

});

test('validates last name', function ($last_name) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'last_name' => $last_name,
        ])
        ->assertJsonValidationErrors(['last_name']);

})
    ->with(['']);

test('requires email address', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'))
        ->assertJsonValidationErrors(['email_address']);

});

test('validates email address', function ($email_address) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'email_address' => $email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'email_address' => $customer->email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

});

test('can create customer', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email_address' => $email_address = fake()->safeEmail(),
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'id' => Customer::latest('id')->first()->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email_address' => $email_address,
            ],
        ]);

});

test('must be authenticated to create customer', function () {

    $this->postJson(route('v1.customers.store'))
        ->assertUnauthorized();

});
