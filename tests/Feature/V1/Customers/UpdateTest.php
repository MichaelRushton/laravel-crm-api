<?php

use App\Models\Customer;
use App\Models\User;

test('must be authenticated to update customer', function () {

    $this->patchJson(route('v1.customers.update', Customer::factory()->create()))
        ->assertUnauthorized();

});

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

test('validates email address', function ($email) {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'email' => $email,
        ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'email' => Customer::factory()->create()->email,
        ])
        ->assertJsonValidationErrors(['email']);

    $this->patchJson(route('v1.customers.update', $customer), [
        'email' => $customer->email,
    ])
        ->assertOk();

});

test('can update customer', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email' => $email = fake()->safeEmail(),
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $customer->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ],
        ]);

});

test('creates revision', function () {

    $customer = Customer::factory()->create();

    $this->sanctum($user = User::factory()->create())
        ->patchJson(route('v1.customers.update', $customer), [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
        ]);

    $customer->refresh();

    expect($customer->revisions()->count())
        ->toBe(2);

    $revision = $customer->revisions->last();

    expect([
        $revision->customer_id,
        $revision->first_name,
        $revision->last_name,
        $revision->email,
        $revision->created_by,
    ])
        ->toBe([
            $customer->id,
            $customer->first_name,
            $customer->last_name,
            $customer->email,
            $user->id,
        ]);

});
