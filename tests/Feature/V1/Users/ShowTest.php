<?php

use App\Models\User;

test('must be authenticated to show user', function () {

    $this->getJson(route('v1.users.show', User::factory()->create()))
        ->assertUnauthorized();

});

test('must be an administrator to show user', function () {

    $this->sanctum($user = User::factory()->create())
        ->getJson(route('v1.users.show', $user))
        ->assertForbidden();

});

test('can show user', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->getJson(route('v1.users.show', $user))
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role->name(),
            ],
        ]);

});
