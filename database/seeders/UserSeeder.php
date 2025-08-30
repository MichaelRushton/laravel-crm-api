<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $count = $this->command->ask('How many users would you like to seed?', 20);

        if ($count && ! User::count()) {

            $count--;

            User::factory()->administrator()->create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
            ]);

        }

        if ($count) {
            User::factory($count)->create();
        }

    }
}
