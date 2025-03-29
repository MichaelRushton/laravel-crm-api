<?php

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;

test('requires product', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.enquiries.store', Customer::factory()->create()))
        ->assertJsonValidationErrors(['product_id']);

});

test('validates product id', function ($product_id) {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.enquiries.store', Customer::factory()->create()), [
            'product_id' => $product_id,
        ])
        ->assertJsonValidationErrors(['product_id']);

})
    ->with([-1]);

test('can create enquiry', function () {

    $this->sanctum(User::factory()->create())
        ->postJson(route('v1.customers.enquiries.store', Customer::factory()->create()), [
            'product_id' => ($product = Product::factory()->create())->id,
        ])
        ->assertCreated()
        ->assertExactJson([
            'data' => [
                'id' => ($enquiry = Enquiry::latest('id')->first())->id,
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

test('must be authenticated to create enquiry', function () {

    $this->postJson(route('v1.customers.enquiries.store', Customer::factory()->create()))
        ->assertUnauthorized();

});
