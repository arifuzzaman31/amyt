<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Define your fake data for each fillable attribute
        return [
            // 'customer_group_id' => \App\Models\CustomerGroup::factory(), // Assuming you have a CustomerGroup model and factory
            // If you don't have a CustomerGroup, you can use a fixed ID or remove this line for now:
            'customer_group_id' => $this->faker->numberBetween(1, 5), // Example: if you have 5 customer groups
            'name' => $this->faker->name, // Generates a random name
            'address' => $this->faker->address, // Generates a random street address
            'company_name' => $this->faker->company, // Generates a random company name
            'email' => $this->faker->unique()->safeEmail, // Generates a unique, safe email address
            'phone' => str_shuffle(time()), // Generates a random phone number
            'type' => 'wholesale', // Randomly picks 'wholesale' or 'retail'
            'status' => $this->faker->boolean(90), // 90% chance of being true (active), 10% false (inactive)
        ];
    }
}
