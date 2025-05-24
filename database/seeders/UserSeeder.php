<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->administrator()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email_address' => 'admin@example.com',
        ]);

        User::factory(19)->create();

    }
}
