<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('can list users', function () {

    $users = User::factory(2)->administrator()->create();

    $this->sanctum($users[0])
        ->getJson(route('v1.users.index'))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) use ($users) {

            $json->has('data', $users->count());

            foreach ($users as $i => $user) {

                $json->has("data.$i", fn (AssertableJson $json) => $json->where('id', $user->id)
                    ->where('first_name', $user->first_name)
                    ->where('last_name', $user->last_name)
                    ->where('email_address', $user->email_address)
                    ->has('role', fn (AssertableJson $json) => $json->where('id', $user->role->value)
                        ->where('name', $user->role->name)
                    )
                );

            }

            $json->has('links')
                ->has('meta')
                ->has('request_time');

        });

});

test('can filter users', function ($data) {

    $user = User::factory()->create($data);

    $this->sanctum(User::factory()->administrator()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email_address' => 'admin@example.com',
    ]))
        ->getJson(route('v1.users.index', ['filter' => $data]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->where('data.0.id', $user->id)
            ->etc()
        );

})
    ->with([
        'first name' => [['first_name' => 'John']],
        'last name' => [['last_name' => 'Smith']],
        'email address' => [['email_address' => 'john.smith@example.com']],
    ]);

test('can sort users', function ($column) {

    $user = User::factory()->administrator()->create([$column => 'B']);
    $user2 = User::factory()->create([$column => 'A']);

    $this->sanctum($user);

    $this->getJson(route('v1.users.index', ['sort' => [$column => 'asc']]))
        ->assertJson(fn (AssertableJson $json) => $json->where('data.0.id', $user2->id)
            ->where('data.1.id', $user->id)
            ->etc()
        );

    $this->getJson(route('v1.users.index', ['sort' => [$column => 'desc']]))
        ->assertJson(fn (AssertableJson $json) => $json->where('data.0.id', $user->id)
            ->where('data.1.id', $user2->id)
            ->etc()
        );

})
    ->with(['first_name', 'last_name', 'email_address']);

test('can change number of users per page', function () {

    User::factory()->create();

    $this->sanctum(User::factory()->administrator()->create())
        ->getJson(route('v1.users.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});

test('must be authenticated to list users', function () {

    $this->getJson(route('v1.users.index'))
        ->assertUnauthorized();

});

test('must be an administrator to list users', function () {

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.users.index'))
        ->assertForbidden();

});
