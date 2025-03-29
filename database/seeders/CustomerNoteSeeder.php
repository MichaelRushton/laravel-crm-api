<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerNoteSeeder extends Seeder
{
    public function run(): void
    {

        CustomerNote::factory(100)
            ->recycle(Customer::all())
            ->recycle(User::all())
            ->create();

    }
}
