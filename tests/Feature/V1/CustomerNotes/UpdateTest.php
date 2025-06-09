<?php

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;

test('validates note text', function ($note) {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.notes.update', [$note->customer, $note]), [
            'note' => $note,
        ])
        ->assertJsonValidationErrors(['note']);

})
    ->with(['']);

test('can update note', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.notes.update', [$note->customer, $note]), [
            'note' => $note_text = fake()->paragraphs(asText: true),
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $note->id,
                'note' => $note_text,
                'created_at' => $note->created_at->toIsoString(),
                'created_by' => [
                    'id' => $note->createdBy->id,
                    'first_name' => $note->createdBy->first_name,
                    'last_name' => $note->createdBy->last_name,
                ],
            ],
        ]);

});

test('must update note from correct customer', function () {

    $note = CustomerNote::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.notes.update', [Customer::factory()->create(), $note]), [
            'note' => fake()->paragraphs(asText: true),
        ])
        ->assertNotFound();

});

test('must be authenticated to update note', function () {

    $note = CustomerNote::factory()->create();

    $this->patchJson(route('v1.customers.notes.update', [$note->customer, $note]))
        ->assertUnauthorized();

});
