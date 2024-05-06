<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\User;
use App\Models\Mentor;
use App\Models\Orientation;
use App\Models\Patient;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SpiritistCenter;
use App\Models\TypeOfTreatment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@lumo.test',
            'password' => Hash::make('password'),
        ]);
        User::factory(20)->create();
        Patient::factory(439)->create();
        Mentor::factory(20)->create();
        Orientation::factory(20)->create();
        Medicine::factory(439)->create();
        TypeOfTreatment::factory(20)->create();
    }
}
