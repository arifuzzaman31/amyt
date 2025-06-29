<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = ['Retail', 'Wholesale', 'VIP'];

        foreach ($groups as $group) {
            CustomerGroup::create([
                'name' => $group,
                'status' => 1,
            ]);
        }
        
        $groupIds = CustomerGroup::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'customer_group_id' => $groupIds[array_rand($groupIds)],
                'name' => "Customer $i",
                'company_name' => "Company $i Ltd.",
                'address' => "123 Street $i, City",
                'email' => "customer$i@example.com",
                'phone' => "017100000$i",
                'type' => ['retail', 'wholesale'][rand(0, 1)],
                'status' => 1,
            ]);
        }
    }
}
