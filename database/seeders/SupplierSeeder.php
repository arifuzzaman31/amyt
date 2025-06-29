<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['local', 'international'];

        for ($i = 1; $i <= 10; $i++) {
            Supplier::create([
                'name' => "Supplier $i",
                'company_name' => "Company $i Ltd.",
                'address' => "Street $i, Industrial Zone",
                'email' => "supplier$i@example.com",
                'phone' => "018100000$i",
                'type' => $types[array_rand($types)],
                'status' => 1,
            ]);
        }
    }
}
