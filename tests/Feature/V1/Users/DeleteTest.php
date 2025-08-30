<?php

use App\Models\User;

test('must be authenticated to delete user', function () {

    $this->deleteJson(route('v1.users.destroy', User::factory()->create()))
        ->assertUnauthorized();

});

test('must be an administrator to delete user', function () {

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.users.destroy', User::factory()->create()))
        ->assertForbidden();

});

test('can delete user', function () {

    $this->sanctum(User::factory()->administrator()->create())
        ->deleteJson(route('v1.users.destroy', User::factory()->create()))
        ->assertNoContent();

});
