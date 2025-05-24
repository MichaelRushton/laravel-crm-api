<?php

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;

test('can show note', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.notes.show', [$note->customer, $note]))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $note->id,
                'note' => $note->note,
                'created_at' => $note->created_at,
                'created_by' => [
                    'id' => $note->createdBy->id,
                    'first_name' => $note->createdBy->first_name,
                    'last_name' => $note->createdBy->last_name,
                ],
            ],
        ]);

});

test('must show note from correct customer', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.notes.show', [Customer::factory()->create(), $note]))
        ->assertNotFound();

});

test('must be authenticated to show note', function () {

    $note = CustomerNote::factory()->create();

    $this->getJson(route('v1.customers.notes.show', [$note->customer, $note]))
        ->assertUnauthorized();

});
