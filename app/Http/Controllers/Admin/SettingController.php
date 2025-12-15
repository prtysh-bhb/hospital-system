<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingCategory;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settingscategory = SettingCategory::where('status', 1)->get();
        $Setting = Setting::all();

        return view('admin.settings.index', compact('Setting', 'settingscategory'));
    }

    /**
     * Update multiple settings (for category-based saving)
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.setting_id' => 'required|exists:settings,id',
            'settings.*.value' => 'nullable',
        ]);
        $category = SettingCategory::find($request->category_id);

        try {
            $updatedCount = 0;

            foreach ($request->settings as $settingData) {
                $setting = Setting::findOrFail($settingData['setting_id']);
                $value = $settingData['value'];

                // Validate and process value based on type
                switch ($setting->type) {
                    case 'integer':
                        if ($value !== null && $value !== '' && ! is_numeric($value)) {
                            return response()->json([
                                'success' => false,
                                'message' => "Value for '{$setting->key}' must be a number",
                            ], 422);
                        }
                        $value = (string) intval($value);
                        break;

                    case 'boolean':
                        $value = ($value === '1' || $value === 'true' || $value === true) ? '1' : '0';
                        break;

                    case 'json':
                        // Validate JSON format
                        if ($value && json_decode($value) === null && json_last_error() !== JSON_ERROR_NONE) {
                            return response()->json([
                                'success' => false,
                                'message' => "Invalid JSON format for '{$setting->key}'",
                            ], 422);
                        }
                        break;

                    default:
                        $value = (string) $value;
                }

                // Update the setting
                $setting->update(['value' => $value]);
            }

            return response()->json([
                'success' => true,
                'message' => ' setting updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: '.$e->getMessage(),
            ], 500);
        }
    }
}
