<?php

use App\Enums\UserRole;
use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Support\Facades\Hash;

test('must be authenticated to create user', function () {

    $this->postJson(route('v1.users.store'))
        ->assertUnauthorized();

});

test('must be an administrator to create user', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.users.store'))
        ->assertForbidden();

});

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
        ->assertJsonValidationErrors(['email']);

});

test('validates email address', function ($email) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'email' => $email,
        ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('email address must be unique', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'email' => $user->email,
        ])
        ->assertJsonValidationErrors(['email']);

});

test('requires role', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'))
        ->assertJsonValidationErrors(['role']);

});

test('validates role', function ($role) {

    $this->sanctum(User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'role' => $role,
        ])
        ->assertJsonValidationErrors(['role']);

})
    ->with(['', 'test']);

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
            'email' => $email = fake()->safeEmail(),
            'role' => UserRole::User->value,
            'password' => $password = str_repeat('a', PasswordService::MIN_LENGTH),
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'id' => ($user = User::latest('id')->first())->id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'role' => UserRole::User->name(),
            ],
        ]);

    expect(Hash::check($password, $user->password))
        ->toBeTrue();

});

test('creates revision', function () {

    $this->sanctum($admin = User::factory()->administrator()->create())
        ->postJson(route('v1.users.store'), [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'role' => UserRole::User->value,
            'password' => str_repeat('a', PasswordService::MIN_LENGTH),
        ]);

    $user = User::orderByDesc('id')->first();

    expect($user->revisions()->count())
        ->toBe(1);

    $revision = $user->revisions->first();

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
            $admin->id,
        ]);

});
