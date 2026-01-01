<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\PatientProfile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manual predefined settings
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
                'setting_category_id' => 3,
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

        // // User fields - Only fields that appear in Step 3 booking form
        // $userFields = [
        //     'first_name',
        //     'last_name',
        //     'email',
        //     'phone',
        //     'date_of_birth',
        //     'gender',
        // ];

        // // Appointment fields - Only fields that appear in Step 3 booking form
        // $appointmentFields = [
        //     'reason_for_visit',
        // ];

        // // Patient Profile fields - Only fields that appear in Step 3 booking form
        // $patientFields = [
        //     'address',
        //     'allergies',
        //     'emergency_contact_name',
        //     'emergency_contact_phone',
        //     'blood_group',
        //     'medical_history',
        //     'current_medications',
        //     'insurance_provider',
        //     'insurance_number',
        // ];
        // User fields (exclude sensitive/system fields)
        $userExcludedFields = [
            'password',
            'role',
            'remember_token',
            'status',
            'profile_image',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
        $userFields = collect((new User)->getFillable())
            ->diff($userExcludedFields)
            ->values()
            ->toArray();

        // Appointment fields (exclude system/auto fields)
        $appointmentExcludedFields = [
            'appointment_number',
            'cancelled_at',
            'booked_by',
            'booked_via',
            'reminder_sent',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
        $appointmentFields = collect((new Appointment)->getFillable())
            ->diff($appointmentExcludedFields)
            ->values()
            ->toArray();

        $patientProfileExcludedFields = [
            'user_id',
            'emergency_contact_phone',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
        $patientFields = collect((new PatientProfile)->getFillable())
            ->diff($patientProfileExcludedFields)
            ->values()
            ->toArray();

        // Add a combined setting for insurance details visibility
        $hasInsuranceSettings = false;

        // Add user fields to settings with category_id 4
        foreach ($userFields as $field) {
            $value = in_array($field, ['first_name', 'phone']) ? '1' : '0';
            $settings[] = [
                'key' => 'show_'.$field,
                'value' => $value,
                'type' => 'boolean',
                'setting_category_id' => 4,
                'description' => "Show/Hide User field: {$field}",
            ];
        }

        // Add appointment fields to settings with category_id 4
        foreach ($appointmentFields as $field) {
            $settings[] = [
                'key' => 'show_'.$field,
                'value' => '0',
                'type' => 'boolean',
                'setting_category_id' => 4,
                'description' => "Show/Hide Appointment field: {$field}",
            ];
        }

        // Add Patient fields to settings with category_id 4
        foreach ($patientFields as $field) {
            $settings[] = [
                'key' => 'show_'.$field,
                'value' => '0',
                'type' => 'boolean',
                'setting_category_id' => 4,
                'description' => "Show/Hide Patient Profile field: {$field}",
            ];
        }

        // Add combined insurance details setting
        $settings[] = [
            'key' => 'show_insurance_details',
            'value' => '0',
            'type' => 'boolean',
            'setting_category_id' => 4,
            'description' => 'Show/Hide Insurance Details fields (provider and number)',
        ];

        // Insert or update settings
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
