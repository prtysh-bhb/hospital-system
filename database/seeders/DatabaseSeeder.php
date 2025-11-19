<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            SpecialtySeeder::class,
            UserSeeder::class,
            DoctorProfileSeeder::class,
            PatientProfileSeeder::class,
            DoctorScheduleSeeder::class,
            AppointmentSeeder::class,
            PrescriptionSeeder::class,
        ]);
    }
}
