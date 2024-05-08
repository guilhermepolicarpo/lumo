<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\TypeOfTreatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'treatment_type_id' => TypeOfTreatment::factory(),
            'treatment_id' => null,
            'date' => now()->format('Y-m-d'),
            'treatment_mode' => fake()->randomElement(['Presencial', 'A distância']),
            'status' => fake()->randomElement(['Confirmado', 'Em espera', 'Atendido', 'Faltou']),
            'notes' => fake()->sentence(),
            'who_requested_it' => null,
            'who_requested_it_phone' => null,
        ];
    }
}
