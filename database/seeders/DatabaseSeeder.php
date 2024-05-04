<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Patient;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Patient::factory(1039)->create();
    }
}
