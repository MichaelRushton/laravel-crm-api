<?php

use App\Enums\UserRole;
use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Support\Facades\Hash;

test('must be authenticated to update user', function () {

    $this->patchJson(route('v1.users.update', User::factory()->create()))
        ->assertUnauthorized();

});

test('must be an administrator to update user', function () {

    $this->sanctum($user = User::factory()->create())
        ->patchJson(route('v1.users.update', $user))
        ->assertForbidden();

});

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

test('validates email address', function ($email) {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'email' => $email,
        ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'email' => User::factory()->create()->email,
        ])
        ->assertJsonValidationErrors(['email']);

    $this->patchJson(route('v1.users.update', $user), [
        'email' => $user->email,
    ])
        ->assertOk();

});

test('validates role', function ($role) {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'role' => $role,
        ])
        ->assertJsonValidationErrors(['role']);

})
    ->with(['', 'test']);

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
            'email' => $email = fake()->safeEmail(),
            'role' => UserRole::User->value,
            'password' => $password = str_repeat('a', PasswordService::MIN_LENGTH),
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->refresh()->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'role' => $user->role->name(),
            ],
        ]);

    expect(Hash::check($password, $user->password))
        ->toBeTrue();

});

test('creates revision', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->patchJson(route('v1.users.update', $user), [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'role' => UserRole::User->value,
            'password' => str_repeat('a', PasswordService::MIN_LENGTH),
        ]);

    $user->refresh();

    expect($user->revisions()->count())
        ->toBe(2);

    $revision = $user->revisions->last();

    expect([
        $revision->user_id,
        $revision->first_name,
        $revision->last_name,
        $revision->email,
        $revision->role,
        $revision->created_by,
    ])
        ->toBe([
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->role,
            $user->id,
        ]);

});
