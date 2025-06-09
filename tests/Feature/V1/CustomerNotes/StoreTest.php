<?php

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;

test('requires note text', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.notes.store', Customer::factory()->create()))
        ->assertJsonValidationErrors(['note']);

});

test('validates note text', function ($note) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.notes.store', Customer::factory()->create()), [
            'note' => $note,
        ])
        ->assertJsonValidationErrors(['note']);

})
    ->with(['']);

test('can create note', function () {

    $this->sanctum($user = User::factory()->create())
        ->postJson(route('v1.customers.notes.store', Customer::factory()->create()), [
            'note' => $note_text = fake()->paragraphs(asText: true),
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'id' => ($note = CustomerNote::latest('id')->first())->id,
                'note' => $note_text,
                'created_at' => $note->created_at->toIsoString(),
                'created_by' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ],
            ],
        ]);

});

test('must be authenticated to create note', function () {

    $this->postJson(route('v1.customers.notes.store', Customer::factory()->create()))
        ->assertUnauthorized();

});
