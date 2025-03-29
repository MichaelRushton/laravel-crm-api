<?php

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;

test('can show enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.enquiries.show', [$enquiry->customer, $enquiry]))
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $enquiry->id,
                'product' => [
                    'id' => $enquiry->product->id,
                    'category' => [
                        'id' => $enquiry->product->category->id,
                        'name' => $enquiry->product->category->name,
                    ],
                    'name' => $enquiry->product->name,
                    'description' => $enquiry->product->description,
                ],
                'created_at' => $enquiry->created_at,
            ],
        ]);

});

test('must show enquiry from correct customer', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.enquiries.show', [Customer::factory()->create(), $enquiry]))
        ->assertNotFound();

});

test('must be authenticated to show enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->getJson(route('v1.customers.enquiries.show', [$enquiry->customer, $enquiry]))
        ->assertUnauthorized();

});
