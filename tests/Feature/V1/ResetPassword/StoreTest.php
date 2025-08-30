<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

test('validates email address', function ($email) {

    $this->postJson(route('v1.reset-password.store'), [
        'email' => $email,
    ])
        ->assertJsonValidationErrors(['email']);

})
    ->with(['', 'test', 'test@', '@example.com']);

test('queues password reset', function () {

    Queue::fake();

    $this->postJson(route('v1.reset-password.store'), [
        'email' => fake()->safeEmail(),
    ])
        ->assertNoContent();

    Queue::assertCount(1);

});

test('sends password reset', function () {

    Notification::fake();

    $user = User::factory()->create();

    $this->postJson(route('v1.reset-password.store'), [
        'email' => $user->email,
    ])
        ->assertNoContent();

    Notification::assertCount(1);

    Notification::assertSentTo($user, ResetPassword::class);

});
