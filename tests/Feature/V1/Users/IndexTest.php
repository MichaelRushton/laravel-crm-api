<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('must be authenticated to list users', function () {

    $this->getJson(route('v1.users.index'))
        ->assertUnauthorized();

});

test('must be an administrator to list users', function () {

    $this->sanctum(User::factory()->create())
        ->getJson(route('v1.users.index'))
        ->assertForbidden();

});

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
                    ->where('email', $user->email)
                    ->where('role', $user->role->name())
                );

            }

            $json->has('links')
                ->has('meta');

        });

});

test('can filter users', function ($data) {

    $user = User::factory()->create($data);

    $this->sanctum(User::factory()->administrator()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
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
        'email address' => [['email' => 'john.smith@example.com']],
        'role' => [['role' => UserRole::User->value]],
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
    ->with(['first_name', 'last_name', 'email']);

test('can change number of users per page', function () {

    User::factory()->create();

    $this->sanctum(User::factory()->administrator()->create())
        ->getJson(route('v1.users.index', ['per_page' => 1]))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc());

});
