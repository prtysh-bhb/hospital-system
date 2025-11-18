<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $specialties = [
            [
                'name' => 'Cardiology',
                'description' => 'Heart and cardiovascular system specialists',
                'icon' => 'heart',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Children\'s health and development',
                'icon' => 'child',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bones, joints, and musculoskeletal system',
                'icon' => 'bone',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail conditions',
                'icon' => 'skin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neurology',
                'description' => 'Nervous system and brain disorders',
                'icon' => 'brain',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Women\'s reproductive health',
                'icon' => 'female',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dentistry',
                'description' => 'Oral health and dental care',
                'icon' => 'tooth',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Mental health and behavioral disorders',
                'icon' => 'mind',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('specialties')->insert($specialties);
    }
}
