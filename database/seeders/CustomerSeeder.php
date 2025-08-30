<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {

        $count = $this->command->ask('How many customers would you like to seed?', 20);

        Customer::factory($count)->create();

    }
}
