<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patients>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $address = Address::all('id');

        return [
            'address_id' => Address::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'birth' => fake()->date(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
