<?php

namespace Database\Seeders;

use App\Models\Yarn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YarnCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => '40/2',       'count' => '40s',       'type' => 'Cotton'],
            ['name' => '30/1',       'count' => '30s',       'type' => 'Polyester'],
            ['name' => '20s',        'count' => '20s',       'type' => 'Cotton'],
            ['name' => '150/D (0)',  'count' => '150D',      'type' => 'Polyester'],
            ['name' => '16/1',       'count' => '16s',       'type' => 'Viscose'],
            ['name' => '10s Open',   'count' => '10s',       'type' => 'Recycled Cotton'],
            ['name' => '50/1',       'count' => '50s',       'type' => 'Cotton'],
            ['name' => '32/2',       'count' => '32s',       'type' => 'Cotton'],
        ];

        foreach ($data as $item) {
            Yarn::create([
                'name' => $item['name'],
                'count' => $item['count'],
                'type'  => $item['type'],
                'status' => 1,
            ]);
        }
    }
}
