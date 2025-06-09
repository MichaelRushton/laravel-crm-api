<?php

use App\Models\Customer;
use App\Models\User;

test('validates first name', function ($first_name) {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'first_name' => $first_name,
        ])
        ->assertJsonValidationErrors(['first_name']);

})
    ->with(['']);

test('validates last name', function ($last_name) {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'last_name' => $last_name,
        ])
        ->assertJsonValidationErrors(['last_name']);

})
    ->with(['']);

test('validates email address', function ($email_address) {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'email_address' => $email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'email_address' => Customer::factory()->create()->email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

    $this->patchJson(route('v1.customers.update', $customer), [
        'email_address' => $customer->email_address,
    ])
        ->assertOk();

});

test('can update customer', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email_address' => $email_address = fake()->safeEmail(),
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $customer->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email_address' => $email_address,
            ],
        ]);

});

test('must be authenticated to update customer', function () {

    $this->patchJson(route('v1.customers.update', Customer::factory()->create()))
        ->assertUnauthorized();

});
