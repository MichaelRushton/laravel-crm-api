<?php

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;

test('validates product id', function ($product_id) {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.enquiries.update', [$enquiry->customer, $enquiry]), [
            'product_id' => $product_id,
        ])
        ->assertJsonValidationErrors(['product_id']);

})
    ->with([-1]);

test('can update enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.enquiries.update', [$enquiry->customer, $enquiry]), [
            'product_id' => ($product = Product::factory()->create())->id,
        ])
        ->assertOk()
        ->assertExactJson([
            'data' => [
                'id' => $enquiry->id,
                'product' => [
                    'id' => $product->id,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                    'name' => $product->name,
                    'description' => $product->description,
                ],
                'created_at' => $enquiry->created_at,
            ],
        ]);

});

test('must update enquiry from correct customer', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->patchJson(route('v1.customers.enquiries.update', [Customer::factory()->create(), $enquiry]), [
        ])
        ->assertNotFound();

});

test('must be authenticated to update enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->patchJson(route('v1.customers.enquiries.update', [$enquiry->customer, $enquiry]))
        ->assertUnauthorized();

});
