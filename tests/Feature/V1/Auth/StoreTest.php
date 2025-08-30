<?php

use App\Models\User;

test('validates email address', function ($email) {

    $this->postJson(route('v1.auth.store'), [
        'email' => $email,
    ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('validates password', function () {

    $this->postJson(route('v1.auth.store'), [
        'password' => '',
    ])
        ->assertJsonValidationErrors(['password']);

});

test('validates credentials', function () {

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => $password = 'password1234',
    ]);

    $this->postJson(route('v1.auth.store'), [
        'email' => 'wrong@example.com',
        'password' => $password,
    ])
        ->assertUnauthorized();

    $this->postJson(route('v1.auth.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertUnauthorized();

});

test('can authenticate', function () {

    $user = User::factory()->create([
        'password' => $password = 'password1234',
    ]);

    $this->postJson(route('v1.auth.store'), [
        'email' => $user->email,
        'password' => $password,
    ])
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);

});

test('rate limit', function () {

    $this->postJson(route('v1.auth.store'));

    $this->postJson(route('v1.auth.store'))
        ->assertTooManyRequests();

    $this->travel(5)->seconds();

    $this->postJson(route('v1.auth.store'))
        ->assertUnprocessable();

});
