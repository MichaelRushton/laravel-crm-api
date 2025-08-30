<?php

use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

test('requires token', function () {

    $this->post(route('password.update'))
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['token']);

});

test('validates token', function ($token) {

    $this->post(route('password.update'), [
        'token' => $token,
    ])
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['token']);

})
    ->with(['']);

test('requires email address', function () {

    $this->post(route('password.update'))
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['email']);

});

test('validates email address', function ($email) {

    $this->post(route('password.update'), [
        'email' => $email,
    ])
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('requires password', function () {

    $this->post(route('password.update'))
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['password']);

});

test('validates password', function ($password) {

    $this->post(route('password.update'), [
        'password' => $password,
    ])
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['password']);

})
    ->with(['', str_repeat('a', PasswordService::MIN_LENGTH - 1)]);

test('validates password confirmation', function () {

    $this->post(route('password.update'), [
        'password' => 'password1234',
        'password_confirmation' => 'password4321',
    ])
        ->assertRedirectBackWithErrors()
        ->assertInvalid(['password_confirmation']);

});

test('requires valid token', function () {

    $user = User::factory()->create();

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make(fake()->uuid()),
    ]);

    $this->post(route('password.update', [
        'token' => fake()->uuid(),
        'email' => $user->email,
        'password' => $password = 'password4321',
        'password' => $password,
    ]))
        ->assertRedirectBackWithErrors()
        ->assertInvalid('password');

});

test('can reset password', function () {

    $user = User::factory()->create();

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token = fake()->uuid()),
    ]);

    $this->post(route('password.update', [
        'token' => $token,
        'email' => $user->email,
        'password' => $password = 'password4321',
        'password' => $password,
    ]))
        ->assertRedirectBack()
        ->assertSessionHas('flash.success');

    $user->refresh();

    expect(Hash::check($password, $user->password))
        ->toBeTrue();

});
