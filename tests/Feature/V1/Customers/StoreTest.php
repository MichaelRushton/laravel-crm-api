<?php

use App\Models\Customer;
use App\Models\User;

test('must be authenticated to create customer', function () {

    $this->postJson(route('v1.customers.store'))
        ->assertUnauthorized();

});

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
        ->assertJsonValidationErrors(['email']);

});

test('validates email address', function ($email) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'email' => $email,
        ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $customer = Customer::factory()->create();

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'email' => $customer->email,
        ])
        ->assertJsonValidationErrors(['email']);

});

test('can create customer', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email' => $email = fake()->safeEmail(),
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'id' => Customer::latest('id')->first()->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ],
        ]);

});

test('creates revision', function () {

    $this->sanctum($user = User::factory()->create())
        ->postJson(route('v1.customers.store'), [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
        ]);

    $customer = Customer::orderByDesc('id')->first();

    expect($customer->revisions()->count())
        ->toBe(1);

    $revision = $customer->revisions->first();

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
