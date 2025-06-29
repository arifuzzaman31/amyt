<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Office Supplies', 'Travel', 'Utilities', 'Marketing', 'Maintenance'];

        foreach ($categories as $name) {
            ExpenseCategory::create([
                'name' => $name,
                'status' => 1,
            ]);
        }

        $categoryIds = ExpenseCategory::pluck('id')->toArray();

        for ($i = 1; $i <= 20; $i++) {
            Expense::create([
                'expense_category_id' => $categoryIds[array_rand($categoryIds)],
                'expense_date' => Carbon::now()->subDays(rand(0, 30)),
                'amount' => rand(100, 1000),
                'description' => 'Expense for item ' . $i,
                'status' => 1,
            ]);
        }
    }
}
