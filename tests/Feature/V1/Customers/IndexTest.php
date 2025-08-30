<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('must be authenticated to list customers', function () {

    $this->getJson(route('v1.customers.index'))
        ->assertUnauthorized();

});

test('can list customers', function () {

    $customers = Customer::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.index'))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($customers) {

            $json->has('data', $customers->count());

            foreach ($customers as $i => $customer) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $customer->id)
                    ->where('first_name', $customer->first_name)
                    ->where('last_name', $customer->last_name)
                    ->where('email', $customer->email)
                );

            }

            $json->has('links')
                ->has('meta');

        });

});

test('can filter customers', function ($data) {

    $customer = Customer::factory()->create($data);

    Customer::factory()->create([
        'first_name' => 'Test',
        'last_name' => 'Customer',
        'email' => 'test@example.com',
    ]);

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.index', ['filter' => $data]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->where('data.0.id', $customer->id)
            ->etc()
        );

})
    ->with([
        'first name' => [['first_name' => 'John']],
        'last name' => [['last_name' => 'Smith']],
        'email address' => [['email' => 'john.smith@example.com']],
    ]);

test('can sort customers', function ($column) {

    $customer = Customer::factory()->create([$column => 'B']);
    $customer2 = Customer::factory()->create([$column => 'A']);

    $this->sanctum(User::factory()->create());

    $this->getJson(route('v1.customers.index', ['sort' => [$column => 'asc']]))
        ->assertJson(fn (AssertableJson $json) => $json->where('data.0.id', $customer2->id)
            ->where('data.1.id', $customer->id)
            ->etc()
        );

    $this->getJson(route('v1.customers.index', ['sort' => [$column => 'desc']]))
        ->assertJson(fn (AssertableJson $json) => $json->where('data.0.id', $customer->id)
            ->where('data.1.id', $customer2->id)
            ->etc()
        );

})
    ->with(['first_name', 'last_name', 'email']);

test('can change number of customers per page', function () {

    Customer::factory(2)->create();

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.customers.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});
