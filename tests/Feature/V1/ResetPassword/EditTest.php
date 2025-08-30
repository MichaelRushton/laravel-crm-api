<?php

use Inertia\Testing\AssertableInertia;

test('must pass email address', function () {

    $this->get(route('password.reset', fake()->uuid()))
        ->assertNotFound();

});

test('can view password reset page', function () {

    $this->get(route('password.reset', [
        'token' => fake()->uuid(),
        'email' => fake()->safeEmail(),
    ]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('ResetPassword/Edit')
        );

});
