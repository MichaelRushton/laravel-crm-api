<?php

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list enquiries', function () {

    $customer = Customer::factory()->create();

    $enquiries = Enquiry::factory(2)->for($customer)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.enquiries', $customer))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($enquiries) {

            $json->has('data', $enquiries->count());

            foreach ($enquiries as $i => $enquiry) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $enquiry->id)
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

        });

});

test('must be authenticated to list enquiries', function () {

    $this->getJson(route('v1.customers.enquiries', Customer::factory()->create()))
        ->assertUnauthorized();

});
