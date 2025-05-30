<?php

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;

test('can delete note', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.customers.notes.destroy', [$note->customer, $note]))
        ->assertNoContent();

});

test('must delete note from correct customer', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.customers.notes.destroy', [Customer::factory()->create(), $note]))
        ->assertNotFound();

});

test('must be authenticated to delete note', function () {

    $note = CustomerNote::factory()->create();

    $this->deleteJson(route('v1.customers.notes.destroy', [$note->customer, $note]))
        ->assertUnauthorized();

});
