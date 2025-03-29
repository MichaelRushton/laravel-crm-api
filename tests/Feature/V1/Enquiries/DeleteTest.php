<?php

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;

test('can delete enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.customers.enquiries.destroy', [$enquiry->customer, $enquiry]))
        ->assertNoContent();

});

test('must delete enquiry from correct customer', function () {

    $enquiry = Enquiry::factory()->create();

    $this->sanctum(User::factory()->create())
        ->deleteJson(route('v1.customers.enquiries.destroy', [Customer::factory()->create(), $enquiry]))
        ->assertNotFound();

});

test('must be authenticated to delete enquiry', function () {

    $enquiry = Enquiry::factory()->create();

    $this->deleteJson(route('v1.customers.enquiries.destroy', [$enquiry->customer, $enquiry]))
        ->assertUnauthorized();

});
