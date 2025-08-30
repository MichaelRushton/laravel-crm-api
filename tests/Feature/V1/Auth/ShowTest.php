<?php

use App\Models\User;

test('must be authenticated to show user', function () {

    $this->getJson(route('v1.auth.show'))
        ->assertUnauthorized();

});

test('can show user', function () {

    $this->sanctum($user = User::factory()->administrator()->create())
        ->getJson(route('v1.auth.show'))
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
