<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //color, weight, size
        $attributes = [
            ['name' => 'Green', 'type' => 'color','group' => 'product'],
            ['name' => 'Lbs', 'type' => 'weight','group' => 'product'],
            ['name' => 'XLL', 'type' => 'size','group' => 'product'],
            ['name' => 'Kg', 'type' => 'size','group' => 'product'],
        ];
        foreach ($attributes as $attribute) {
            \App\Models\Attribute::create($attribute);
        }
    }
}
