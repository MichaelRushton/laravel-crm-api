<?php

use App\Models\User;
use App\Services\V1\PasswordService;
use Illuminate\Support\Facades\Hash;

test('validates first name', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'first_name' => '',
        ])
        ->assertJsonValidationErrors(['first_name']);

});

test('validates last name', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'last_name' => '',
        ])
        ->assertJsonValidationErrors(['last_name']);

});

test('validates email address', function ($email_address) {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'email_address' => $email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'email_address' => User::factory()->create()->email_address,
        ])
        ->assertJsonValidationErrors(['email_address']);

    $this->patchJson(route('v1.users.update', $user), [
        'email_address' => $user->email_address,
    ])
        ->assertOk();

});

test('validates password', function ($password) {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'password' => $password,
        ])
        ->assertJsonValidationErrors(['password']);

})
    ->with(['', str_repeat('a', PasswordService::MIN_LENGTH - 1)]);

test('can update user', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'first_name' => $first_name = fake()->firstName(),
            'last_name' => $last_name = fake()->lastName(),
            'email_address' => $email_address = fake()->safeEmail(),
            'password' => $password = str_repeat('a', PasswordService::MIN_LENGTH),
        ])
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $user->refresh()->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email_address' => $email_address,
                'role' => [
                    'id' => $user->role->value,
                    'name' => $user->role->name,
                ],
            ],
        ]);

    expect(Hash::check($password, $user->password))
        ->toBeTrue();

});

test('must be authenticated to update user', function () {

    $this->patchJson(route('v1.users.update', User::factory()->create()))
        ->assertUnauthorized();

});

test('must be an administrator to create user', function () {

    $this->sanctum($user = User::factory()->create())
        ->patchJson(route('v1.users.update', $user))
        ->assertForbidden();

});
