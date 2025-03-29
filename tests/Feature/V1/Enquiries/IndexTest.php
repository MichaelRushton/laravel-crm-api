<?php

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list enquiries', function () {

    $enquiries = Enquiry::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.enquiries.index'))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($enquiries) {

            $json->has('data', $enquiries->count());

            foreach ($enquiries->sortBy('id', descending: true)->values() as $i => $enquiry) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $enquiry->id)
                    ->has('customer', fn (AssertableJson $json) => $json->where('id', $enquiry->customer->id)
                        ->where('first_name', $enquiry->customer->first_name)
                        ->where('last_name', $enquiry->customer->last_name)
                        ->where('email_address', $enquiry->customer->email_address)
                    )
                    ->has('product', fn (AssertableJson $json) => $json->where('id', $enquiry->product->id)
                        ->has('category', fn (AssertableJson $json) => $json->where('id', $enquiry->product->category->id)
                            ->where('name', $enquiry->product->category->name)
                        )
                        ->where('name', $enquiry->product->name)
                        ->where('description', $enquiry->product->description)
                    )
                    ->where('created_at', $enquiry->created_at->toISOString())
                );

            }

            $json->has('links')
                ->has('meta');

        });

});

test('can filter enquiries', function ($column) {

    $enquiry = Enquiry::factory()->create();

    Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.enquiries.index', ['filter' => [
            $column => $enquiry->$column,
        ]]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->where('data.0.id', $enquiry->id)
            ->etc()
        );

})
    ->with(['customer_id', 'product_id']);

test('can change number of enquiries per page', function () {

    Enquiry::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.enquiries.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});

test('must be authenticated to list enquiries', function () {

    $this->getJson(route('v1.enquiries.index'))
        ->assertUnauthorized();

});
