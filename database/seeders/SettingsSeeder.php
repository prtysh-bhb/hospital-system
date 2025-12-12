<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Hospital Management System',
                'type' => 'string',
                'setting_category_id' => 1,
                'description' => 'The name of the website',
            ],
            [
                'key' => 'max_appointments_per_day',
                'value' => '20',
                'type' => 'integer',
                'setting_category_id' => 1,
                'description' => 'Maximum number of appointments allowed per day',
            ],
            [
                'key' => 'enable_email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'setting_category_id' => 1,
                'description' => 'Enable or disable email notifications',
            ],
            [
                'key' => 'appointment_booking_days',
                'value' => '60',
                'type' => 'integer',
                'setting_category_id' => 2,
                'description' => 'Number of days in advance a patient can book an appointment',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
