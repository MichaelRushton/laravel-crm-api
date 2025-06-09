<?php

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list notes', function () {

    $customer = Customer::factory()->create();

    $notes = CustomerNote::factory(2)->for($customer)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.notes.index', $customer))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($notes) {

            $json->has('data', $notes->count());

            foreach ($notes as $i => $note) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $note->id)
                    ->where('note', $note->note)
                    ->where('created_at', $note->created_at->toISOString())
                    ->has('created_by', fn (AssertableJson $json) => $json->where('id', $note->createdBy->id)
                        ->where('first_name', $note->createdBy->first_name)
                        ->where('last_name', $note->createdBy->last_name)
                    )
                );

            }

            $json->has('request_time');

        });

});

test('must be authenticated to list notes', function () {

    $this->getJson(route('v1.customers.notes.index', Customer::factory()->create()))
        ->assertUnauthorized();

});
