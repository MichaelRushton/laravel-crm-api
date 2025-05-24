<?php

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Services\V1\PasswordService;
use Illuminate\Support\Facades\Hash;

test('requires first name', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'))
        ->assertJsonValidationErrors(['first_name']);

});

test('validates first name', function ($first_name) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'first_name' => $first_name,
        ])
        ->assertJsonValidationErrors(['first_name']);

})
    ->with(['']);

test('requires last name', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'))
        ->assertJsonValidationErrors(['last_name']);

});

test('validates last name', function ($last_name) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'last_name' => $last_name,
        ])
        ->assertJsonValidationErrors(['last_name']);

})
    ->with(['']);

test('requires email address', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'))
        ->assertJsonValidationErrors(['email_address']);

});

test('validates email address', function ($email_address) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'email_address' => $email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'email_address' => $user->email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

});

test('requires password', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'))
        ->assertJsonValidationErrors(['password']);

});

test('validates password', function ($password) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'password' => $password,
        ])
        ->assertJsonValidationErrors(['password']);

})
    ->with(['', str_repeat('a', PasswordService::MIN_LENGTH - 1)]);

test('can create user', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email_address' => $email_address = fake()->safeEmail(),
            'password' => $password = str_repeat('a', PasswordService::MIN_LENGTH),
        ])
        ->assertCreated()
        ->assertExactJson([
            'data' => [
                'id' => ($user = User::latest('id')->first())->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email_address' => $email_address,
                'role' => [
                    'id' => UserRoleEnum::User->value,
                    'name' => UserRoleEnum::User->name,
                ],
            ],
        ]);

    expect(Hash::check($password, $user->password))
        ->toBeTrue();

});

test('must be authenticated to create user', function () {

    $this->postJson(route('v1.users.store'))
        ->assertUnauthorized();

});

test('must be an administrator to create user', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.users.store'))
        ->assertForbidden();

});
