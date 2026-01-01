<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Setting;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\PatientProfile;
use App\Models\SettingCategory;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        // Get active categories with their settings count
        $categories = SettingCategory::where('status', '1')
            ->orderByRaw('`order` = 0')
            ->orderBy('order', 'asc')
            ->get()->map(function ($category) {
                $settingsCount = Setting::where('setting_category_id', $category->id)
                ->where('status', '1')
                ->count();

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'display_name' => ucwords(str_replace('_', ' ', $category->name)),
                    'settings_count' => $settingsCount,
                ];
        });

        // Get all settings grouped by category
        $settingsByCategory = Setting::whereIn('setting_category_id', $categories->pluck('id'))
            ->where('status', '1')
            ->get()
            ->groupBy('setting_category_id');

        // Format settings data for easy access
        $settings = [];
        foreach ($settingsByCategory as $categoryId => $categorySettings) {
            foreach ($categorySettings as $setting) {
                $settings[$setting->key] = [
                    'value' => $setting->getRawOriginal('value'),
                    'type' => $setting->type,
                    'description' => $setting->description,
                    'category_id' => $setting->setting_category_id,
                ];
            }
        }

        // User fields (exclude sensitive/system fields)
        $userExcludedFields = [
            'password',
            'role',
            'remember_token',
            'status',
            'profile_image',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $userFields = collect((new User())->getFillable())
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
            'deleted_at'
        ];
        $appointmentFields = collect((new Appointment())->getFillable())
            ->diff($appointmentExcludedFields)
            ->values()
            ->toArray();

        $patientProfileExcludedFields = [
            'user_id',
            'emergency_contact_phone',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
        $patientFields = collect((new PatientProfile())->getFillable())
            ->diff($patientProfileExcludedFields)
            ->values()
            ->toArray();

        return view('admin.settings.index', compact('categories', 'settings', 'userFields', 'appointmentFields', 'patientFields'));
    }

    /**
     * Update multiple settings (for category-based saving)
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'required|string',
            'settings.*.category_id' => 'required|integer',
        ]);

        try {
            foreach ($request->settings as $settingData) {
                $value = $settingData['value'];
                $type = $settingData['type'];
                $key = $settingData['key'];
                $categoryId = $settingData['category_id'];

                // Validate and process value based on type
                switch ($type) {
                    case 'integer':
                        if ($value !== null && $value !== '' && ! is_numeric($value)) {
                            return response()->json([
                                'success' => false,
                                'message' => "Value for '{$key}' must be a number",
                            ], 422);
                        }
                        $value = (string) intval($value);
                        
                        // Special validation for appointment_booking_days
                        if ($key === 'appointment_booking_days') {
                            $intValue = (int) $value;
                            if ($intValue <= 0) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Appointment booking days must be greater than 0. Negative values and 0 are not allowed.',
                                ], 422);
                            }
                        }
                        break;

                    case 'boolean':
                        $value = ($value === '1' || $value === 'true' || $value === true) ? '1' : '0';
                        break;

                    case 'json':
                        // Validate JSON format
                        if ($value && json_decode($value) === null && json_last_error() !== JSON_ERROR_NONE) {
                            return response()->json([
                                'success' => false,
                                'message' => "Invalid JSON format for '{$key}'",
                            ], 422);
                        }
                        break;

                    default:
                        $value = (string) $value;
                }

                // Update or create the setting - match on BOTH key AND category_id
                $setting = Setting::where('key', $key)
                    ->where('setting_category_id', $categoryId)
                    ->first();

                if ($setting) {
                    // Update existing
                    $setting->update([
                        'value' => $value,
                        'type' => $type,
                    ]);
                } else {
                    // Create new
                    Setting::create([
                        'key' => $key,
                        'value' => $value,
                        'type' => $type,
                        'setting_category_id' => $categoryId,
                        'description' => '',
                        'status' => 1,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
