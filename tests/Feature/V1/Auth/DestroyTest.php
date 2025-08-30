<?php

use App\Models\User;

test('must be authenticated to sign out', function () {

    $this->deleteJson(route('v1.auth.destroy'))
        ->assertUnauthorized();

});

test('can sign out', function () {

    $user = User::factory()->create();

    $this->sanctum($user = User::factory()->create())
        ->deleteJson(route('v1.auth.destroy'))
        ->assertNoContent();

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

});
