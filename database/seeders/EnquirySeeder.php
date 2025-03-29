<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnquirySeeder extends Seeder
{
    public function run(): void
    {

        Enquiry::factory(100)
            ->recycle(Customer::all())
            ->recycle(Product::all())
            ->recycle(User::all())
            ->create();

    }
}
