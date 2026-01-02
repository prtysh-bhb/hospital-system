@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
    <div class="mx-auto">
        @php
            $generalCat = $categories->where('name', 'general')->first();
            $bookingCat = $categories->where('name', 'public_booking')->first();
            $notifCat = $categories->where('name', 'notifications')->first();
            $formCat = $categories->where('name', 'booking_form')->first();
        @endphp

        {{-- Check if categories are missing and show error message --}}
        @if (!$generalCat || !$bookingCat || !$notifCat || !$formCat)
            <div class="min-h-[70vh] flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 w-full max-w-md">

                    {{-- Warning Icon --}}
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-50 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.282 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Main Message --}}
                    <h2 class="text-xl font-semibold text-gray-800 text-center mb-2">
                        Settings Not Found
                    </h2>

                    <p class="text-gray-600 text-center mb-4">
                        System settings are missing. Please run the seeders.
                    </p>

                    {{-- Missing Items --}}
                    <div class="bg-gray-50 rounded-md p-3 mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Missing Categories:</p>
                        <div class="text-sm text-gray-600">
                            @if (!$generalCat)
                                <div class="mb-1">• General Settings</div>
                            @endif
                            @if (!$bookingCat)
                                <div class="mb-1">• Public Booking Settings</div>
                            @endif
                            @if (!$notifCat)
                                <div class="mb-1">• Notification Settings</div>
                            @endif
                            @if (!$formCat)
                                <div class="mb-1">• Booking Form Settings</div>
                            @endif
                        </div>
                    </div>

                    {{-- Solution --}}
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-3 mb-5">
                        <p class="text-sm font-medium text-blue-800 mb-2">Fix this by running:</p>
                        <div class="space-y-2">
                            <div class="bg-gray-800 text-white text-xs p-2 rounded font-mono overflow-x-auto">
                                php artisan db:seed --class=SettingCategories
                            </div>
                            {{-- <div class="bg-gray-800 text-white text-xs p-2 rounded font-mono overflow-x-auto">
                                php artisan db:seed --class=SettingsSeeder
                            </div> --}}
                        </div>
                    </div>

                    {{-- Note --}}
                    <p class="text-xs text-gray-500 text-center">
                        After running commands, refresh this page.
                    </p>
                </div>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <div class="lg:w-100 xl:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Sidebar Header -->
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Settings Categories</h3>
                            <p class="text-sm text-gray-500">Manage your system configuration</p>
                        </div>
                        <div class="p-2">
                            <!-- Static categories -->
                            <nav class="space-y-1">
                                <!-- General Settings -->
                                <button type="button" onclick="switchCategory('general')" id="category_general"
                                    class="category-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 active-category bg-sky-50 text-sky-700 border border-sky-100">
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200 bg-sky-500 text-white shadow-sm">
                                        <i class="fas fa-cog text-xs"></i>
                                    </span>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">{{ $generalCat['display_name'] ?? 'General' }}</div>
                                        <div class="text-xs mt-0.5 text-sky-600">{{ $generalCat['settings_count'] ?? 0 }}
                                            {{ Str::plural('setting', $generalCat['settings_count'] ?? 0) }}</div>
                                    </div>
                                </button>

                                <!-- Public Booking Settings -->
                                <button type="button" onclick="switchCategory('public_booking')"
                                    id="category_public_booking"
                                    class="category-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200 bg-gray-100 text-gray-500">
                                        <i class="fas fa-calendar-check text-xs"></i>
                                    </span>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">{{ $bookingCat['display_name'] ?? 'Public Booking' }}</div>
                                        <div class="text-xs mt-0.5 text-gray-400">{{ $bookingCat['settings_count'] ?? 0 }}
                                            {{ Str::plural('setting', $bookingCat['settings_count'] ?? 0) }}</div>
                                    </div>
                                </button>

                                <!-- Notifications Settings -->
                                <button type="button" onclick="switchCategory('notifications')" id="category_notifications"
                                    class="category-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200 bg-gray-100 text-gray-500">
                                        <i class="fas fa-bell text-xs"></i>
                                    </span>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">{{ $notifCat['display_name'] ?? 'Notifications' }}</div>
                                        <div class="text-xs mt-0.5 text-gray-400">{{ $notifCat['settings_count'] ?? 0 }}
                                            {{ Str::plural('setting', $notifCat['settings_count'] ?? 0) }}</div>
                                    </div>
                                </button>

                                <!-- Booking Form Settings -->
                                <button type="button" onclick="switchCategory('booking_form')" id="category_booking_form"
                                    class="category-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200 bg-gray-100 text-gray-500">
                                        <i class="fas fa-file-medical text-xs"></i>
                                    </span>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">{{ $formCat['display_name'] ?? 'Booking Form' }}</div>
                                        <div class="text-xs mt-0.5 text-gray-400">{{ $formCat['settings_count'] ?? 0 }}
                                            {{ Str::plural('setting', $formCat['settings_count'] ?? 0) }}</div>
                                    </div>
                                </button>
                            </nav>

                            <!-- Dynamically generate categories (commented out) -->
                            {{-- <nav class="space-y-1">
                            <!-- Loop through all active categories -->
                            @foreach ($categories as $category)
                                <button type="button" onclick="switchCategory('{{ $category['name'] }}')"
                                    id="category_{{ $category['name'] }}"
                                    class="category-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ $loop->first ? 'active-category bg-sky-50 text-sky-700 border border-sky-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200 {{ $loop->first ? 'bg-sky-500 text-white' : 'bg-gray-100 text-gray-500' }}">
                                        <i class="fas fa-cog text-xs"></i>
                                        <!-- You can change this icon per category if you want -->
                                    </span>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">{{ $category['display_name'] }}
                                        </div>
                                        <div class="text-xs mt-0.5 text-sky-600">
                                            {{ $category['settings_count'] ?? 0 }}
                                            {{ Str::plural('setting', $category['settings_count'] ?? 0) }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </nav> --}}
                        </div>

                        <!-- Sidebar Footer -->
                        <div class="p-4 border-t border-gray-100 bg-gray-50">
                            <div class="text-xs text-gray-500">
                                Changes are saved per category
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1">

                    <!-- GENERAL SETTINGS -->
                    <div id="content_general"
                        class="category-content bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-100 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-sky-100 to-sky-50 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-cog text-sky-600"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">
                                            {{ $generalCat['display_name'] ?? 'General' }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">Configure your
                                            {{ strtolower($generalCat['display_name'] ?? 'general') }} preferences</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full font-medium">
                                        {{ $generalCat['settings_count'] ?? 0 }}
                                        {{ Str::plural('setting', $generalCat['settings_count'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
                                <!-- Site Name -->
                                @if (isset($settings['site_name']))
                                    <div class="setting-card bg-white border-2 border-gray-100 rounded-xl p-5 hover:border-sky-200 hover:shadow-md transition-all duration-200"
                                        data-category-id="{{ $settings['site_name']['category_id'] }}">
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 truncate">
                                                    {{ ucwords(str_replace('_', ' ', 'site_name')) }}</h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $settings['site_name']['description'] }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-6 space-y-2">
                                            <label class="text-sm font-medium text-gray-700">Value</label>
                                            <input type="text"
                                                class="setting-input w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-sky-100 focus:border-sky-400 transition-all duration-200 hover:border-gray-200"
                                                data-setting-key="site_name"
                                                data-category-id="{{ $settings['site_name']['category_id'] }}"
                                                data-setting-type="{{ $settings['site_name']['type'] }}"
                                                value="{{ $settings['site_name']['value'] }}" placeholder="Enter value">
                                        </div>
                                    </div>
                                @endif

                                <!-- Max Appointments Per Day -->
                                @if (isset($settings['max_appointments_per_day']))
                                    <div class="setting-card bg-white border-2 border-gray-100 rounded-xl p-5 hover:border-sky-200 hover:shadow-md transition-all duration-200"
                                        data-category-id="{{ $settings['max_appointments_per_day']['category_id'] }}">
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 truncate">
                                                    {{ ucwords(str_replace('_', ' ', 'max_appointments_per_day')) }}
                                                </h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $settings['max_appointments_per_day']['description'] }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-6 space-y-2">
                                            <label class="text-sm font-medium text-gray-700">Value</label>
                                            <input type="number"
                                                class="setting-input w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-sky-100 focus:border-sky-400 transition-all duration-200 hover:border-gray-200"
                                                data-setting-key="max_appointments_per_day"
                                                data-category-id="{{ $settings['max_appointments_per_day']['category_id'] }}"
                                                data-setting-type="{{ $settings['max_appointments_per_day']['type'] }}"
                                                value="{{ $settings['max_appointments_per_day']['value'] }}"
                                                min="0" placeholder="Enter number">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-6 mt-6 border-t border-gray-100">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-gray-500">Your changes will be applied immediately after
                                        saving
                                    </div>
                                    <button type="button"
                                        class="save-category-btn inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all duration-200 hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none shadow-sm"
                                        data-category-id="{{ $generalCat['id'] ?? 1 }}">
                                        <i class="fas fa-save"></i>
                                        <span>Save {{ $generalCat['display_name'] ?? 'General' }} Settings</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PUBLIC BOOKING SETTINGS -->
                    <div id="content_public_booking"
                        class="category-content bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden">
                        <div class="border-b border-gray-100 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-sky-100 to-sky-50 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-sky-600"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">
                                            {{ $bookingCat['display_name'] ?? 'Public Booking' }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">Configure your
                                            {{ strtolower($bookingCat['display_name'] ?? 'public booking') }} preferences
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full font-medium">
                                        {{ $bookingCat['settings_count'] ?? 0 }}
                                        {{ Str::plural('setting', $bookingCat['settings_count'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
                                <!-- Appointment Booking Days -->
                                @if (isset($settings['appointment_booking_days']))
                                    <div class="setting-card bg-white border-2 border-gray-100 rounded-xl p-5 hover:border-sky-200 hover:shadow-md transition-all duration-200"
                                        data-category-id="{{ $settings['appointment_booking_days']['category_id'] }}">
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1 truncate">
                                                    {{ ucwords(str_replace('_', ' ', 'appointment_booking_days')) }}
                                                </h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $settings['appointment_booking_days']['description'] }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-6 space-y-2">
                                            <label class="text-sm font-medium text-gray-700">Value</label>
                                            <input type="number"
                                                class="setting-input w-full border-2 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-sky-100 focus:border-sky-400 transition-all duration-200 hover:border-gray-200"
                                                data-setting-key="appointment_booking_days"
                                                data-category-id="{{ $settings['appointment_booking_days']['category_id'] }}"
                                                data-setting-type="{{ $settings['appointment_booking_days']['type'] }}"
                                                value="{{ $settings['appointment_booking_days']['value'] }}"
                                                min="0" placeholder="Enter number">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-6 mt-6 border-t border-gray-100">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-gray-500">Your changes will be applied immediately after
                                        saving
                                    </div>
                                    <button type="button"
                                        class="save-category-btn inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all duration-200 hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none shadow-sm"
                                        data-category-id="{{ $bookingCat['id'] ?? 2 }}">
                                        <i class="fas fa-save"></i>
                                        <span>Save {{ $bookingCat['display_name'] ?? 'Public Booking' }} Settings</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- NOTIFICATIONS SETTINGS -->
                    <div id="content_notifications"
                        class="category-content bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden">
                        <div class="border-b border-gray-100 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-sky-100 to-sky-50 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-bell text-sky-600"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">
                                            {{ $notifCat['display_name'] ?? 'Notifications' }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">Configure your
                                            {{ strtolower($notifCat['display_name'] ?? 'notifications') }} preferences</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full font-medium">
                                        {{ $notifCat['settings_count'] ?? 0 }}
                                        {{ Str::plural('setting', $notifCat['settings_count'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            @if (isset($settings['whatsapp_notifications']))
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($settings as $key => $setting)
                                        @if (in_array($key, ['whatsapp_notifications']))
                                            @php
                                                $settingValue = $setting['value'] ?? '0';
                                            @endphp

                                            <label
                                                class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer">
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </p>
                                                </div>

                                                <input type="checkbox"
                                                    class="setting-input w-5 h-5 text-sky-600 cursor-pointer"
                                                    data-setting-key="{{ $key }}" data-setting-type="boolean"
                                                    data-category-id="{{ $notifCat['id'] ?? 3 }}"
                                                    {{ $settingValue == '1' ? 'checked' : '' }}>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="pt-6 mt-6 border-t border-gray-100">
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                        <div class="text-sm text-gray-500">Your changes will be applied immediately after
                                            saving
                                        </div>
                                        <button type="button"
                                            class="save-category-btn inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all duration-200 hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none shadow-sm"
                                            data-category-id="{{ $notifCat['id'] ?? 3 }}">
                                            <i class="fas fa-save"></i>
                                            <span>Save {{ $notifCat['display_name'] ?? 'Notifications' }} Settings</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-12">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-bell text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Settings Available</h3>
                                    <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                                        There are no configuration options for this category yet. Add new settings as
                                        needed.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- BOOKING FORM SETTINGS -->
                    <div id="content_booking_form"
                        class="category-content bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden">
                        <div class="border-b border-gray-100 p-6 lg:p-8">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-sky-100 to-sky-50 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-file-medical text-sky-600"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">
                                            {{ $formCat['display_name'] ?? 'Booking Form' }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">Configure patient form fields visibility</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full font-medium">
                                        {{ $formCat['settings_count'] ?? 0 }}
                                        {{ Str::plural('setting', $formCat['settings_count'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            <div class="space-y-8">
                                {{-- ================= USER FIELDS ================= --}}
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information Fields</h3>
                                    @php
                                        $mandatoryUserFields = ['first_name', 'phone'];
                                    @endphp

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach ($userFields as $field)
                                            @php
                                                $settingKey = 'show_' . $field;
                                                $isMandatory = in_array($field, $mandatoryUserFields);
                                                $settingValue = isset($settings[$settingKey])
                                                    ? $settings[$settingKey]['value']
                                                    : '0';
                                            @endphp

                                            <label
                                                class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer"
                                                @if ($isMandatory) title="This field is mandatory and cannot be hidden" @endif>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">
                                                        {{ ucwords(str_replace('_', ' ', $field)) }}
                                                        @if ($isMandatory)
                                                            <span class="text-red-600 text-xs ml-1">(Required)</span>
                                                        @endif
                                                    </p>
                                                </div>

                                                <input type="checkbox"
                                                    class="setting-input w-5 h-5 text-sky-600 cursor-pointer"
                                                    data-setting-key="{{ $settingKey }}" data-setting-type="boolean"
                                                    data-category-id="{{ $formCat['id'] }}"
                                                    {{ $isMandatory ? 'checked disabled' : ($settingValue == '1' ? 'checked' : '') }}>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ================= APPOINTMENT FIELDS ================= --}}
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Appointment Information Fields
                                    </h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach ($appointmentFields as $field)
                                            @php
                                                $settingKey = 'show_' . $field;
                                                $settingValue = isset($settings[$settingKey])
                                                    ? $settings[$settingKey]['value']
                                                    : '0';
                                            @endphp

                                            <label
                                                class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer">
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">
                                                        {{ ucwords(str_replace('_', ' ', $field)) }}
                                                    </p>
                                                </div>

                                                <input type="checkbox"
                                                    class="setting-input w-5 h-5 text-sky-600 cursor-pointer"
                                                    data-setting-key="{{ $settingKey }}" data-setting-type="boolean"
                                                    data-category-id="{{ $formCat['id'] }}"
                                                    {{ $settingValue == '1' ? 'checked' : '' }}>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ================= PATIENT FIELDS ================= --}}
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Patient Profile Fields</h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach ($patientFields as $field)
                                            @php
                                                $settingKey = 'show_' . $field;
                                                $settingValue = isset($settings[$settingKey])
                                                    ? $settings[$settingKey]['value']
                                                    : '0';
                                            @endphp

                                            <label
                                                class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer">
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">
                                                        {{ ucwords(str_replace('_', ' ', $field)) }}
                                                    </p>
                                                </div>

                                                <input type="checkbox"
                                                    class="setting-input w-5 h-5 text-sky-600 cursor-pointer"
                                                    data-setting-key="{{ $settingKey }}" data-setting-type="boolean"
                                                    data-category-id="{{ $formCat['id'] }}"
                                                    {{ $settingValue == '1' ? 'checked' : '' }}>
                                            </label>
                                        @endforeach

                                        <!-- Insurance Details Combined Setting -->
                                        @php
                                            $insuranceSettingKey = 'show_insurance_details';
                                            $insuranceSettingValue = isset($settings[$insuranceSettingKey])
                                                ? $settings[$insuranceSettingKey]['value']
                                                : '0';
                                        @endphp
                                        <label
                                            class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition-all cursor-pointer">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-700">
                                                    Insurance Details
                                                </p>
                                            </div>

                                            <input type="checkbox"
                                                class="setting-input w-5 h-5 text-sky-600 cursor-pointer"
                                                data-setting-key="{{ $insuranceSettingKey }}" data-setting-type="boolean"
                                                data-category-id="{{ $formCat['id'] }}"
                                                {{ $insuranceSettingValue == '1' ? 'checked' : '' }}>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 mt-6 border-t border-gray-100">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-gray-500">Your changes will be applied immediately after
                                        saving
                                    </div>
                                    <button type="button"
                                        class="save-category-btn inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all duration-200 hover:shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none shadow-sm"
                                        data-category-id="{{ $formCat['id'] ?? 5 }}">
                                        <i class="fas fa-save"></i>
                                        <span>Save {{ $formCat['display_name'] ?? 'Booking Form' }} Settings</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Category button hover effects */
        .category-btn:hover .w-9.h-9 {
            background-color: #e0f2fe !important;
            color: #0284c7 !important;
            transform: scale(1.05);
        }

        .active-category {
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.1);
        }

        /* Setting card animations */
        .setting-card {
            transition: all 0.3s ease;
        }

        .setting-card:hover {
            transform: translateY(-2px);
        }

        /* Success animation */
        @keyframes flashSuccess {

            0%,
            100% {
                background-color: transparent;
                border-color: #f1f5f9;
            }

            50% {
                background-color: #f0f9ff;
                border-color: #38bdf8;
                box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
            }
        }

        .flash-success {
            animation: flashSuccess 1s ease-in-out;
        }

        /* JSON validation styling */
        .json-valid {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }

        .json-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function switchCategory(categoryId) {
            // Hide all category contents
            document.querySelectorAll('.category-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active-category', 'bg-sky-50', 'text-sky-700', 'border', 'border-sky-100');
                btn.classList.add('text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900');

                // Reset icon colors
                const iconSpan = btn.querySelector('.w-9.h-9');
                if (iconSpan) {
                    iconSpan.classList.remove('bg-sky-500', 'text-white', 'shadow-sm');
                    iconSpan.classList.add('bg-gray-100', 'text-gray-500');
                }

                // Remove chevron
                const chevron = btn.querySelector('.fa-chevron-right');
                if (chevron) chevron.remove();
            });

            // Show selected category content
            const selectedContent = document.getElementById(`content_${categoryId}`);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');

                // Smooth scroll to top of content
                setTimeout(() => {
                    selectedContent.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }

            // Add active state to selected category button
            const selectedBtn = document.getElementById(`category_${categoryId}`);
            if (selectedBtn) {
                selectedBtn.classList.add('active-category', 'bg-sky-50', 'text-sky-700', 'border', 'border-sky-100');
                selectedBtn.classList.remove('text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900');

                // Update icon
                const iconSpan = selectedBtn.querySelector('.w-9.h-9');
                if (iconSpan) {
                    iconSpan.classList.add('bg-sky-500', 'text-white', 'shadow-sm');
                    iconSpan.classList.remove('bg-gray-100', 'text-gray-500');
                }

                // Add chevron
                const chevron = document.createElement('i');
                chevron.className = 'fas fa-chevron-right text-sky-500 text-xs ml-2';
                selectedBtn.appendChild(chevron);
            }
        }
        // Initialize - show first category by default
        document.addEventListener('DOMContentLoaded', function() {
            const firstCategoryBtn = document.querySelector('.category-btn');

            if (firstCategoryBtn) {
                const categoryId = firstCategoryBtn.id.replace('category_', '');
                switchCategory(categoryId);

                // optional: agar future use ke liye save rakhna ho
                localStorage.setItem('activeCategory', categoryId);
            }
        });

        // Handle boolean toggle text update
        $(document).on('change', '.setting-input[data-setting-type="boolean"]', function() {
            const $card = $(this).closest('.setting-card');
            const $statusText = $card.find(
                '.text-sm.font-medium.text-gray-700, .text-sm.font-medium.text-green-600');
            const settingKey = $(this).data('setting-key');

            if ($(this).is(':checked')) {
                // Check if it's a visibility setting (show_*)
                if (settingKey && settingKey.startsWith('show_')) {
                    $statusText.text('Visible').removeClass('text-gray-700').addClass('text-green-600');
                } else {
                    $statusText.text('Enabled').removeClass('text-gray-700').addClass('text-green-600');
                }
            } else {
                // Check if it's a visibility setting (show_*)
                if (settingKey && settingKey.startsWith('show_')) {
                    $statusText.text('Hidden').removeClass('text-green-600').addClass('text-gray-700');
                } else {
                    $statusText.text('Disabled').removeClass('text-green-600').addClass('text-gray-700');
                }
            }

            // Visual feedback
            $card.addClass('flash-success');
            setTimeout(() => {
                $card.removeClass('flash-success');
            }, 1000);
        });

        // Real-time JSON validation
        $(document).on('input', '.setting-input[data-setting-type="json"]', function() {
            const $textarea = $(this);
            const $icon = $textarea.siblings('.absolute').find('i');
            const $card = $textarea.closest('.setting-card');

            try {
                if ($textarea.val().trim()) {
                    JSON.parse($textarea.val());
                    $icon.removeClass('text-gray-400 text-red-400').addClass('text-green-500');
                    $textarea.removeClass('json-invalid').addClass('json-valid');
                    $card.find('.bg-amber-100').addClass('bg-green-100 text-green-800');
                } else {
                    $icon.removeClass('text-red-400 text-green-500').addClass('text-gray-400');
                    $textarea.removeClass('json-invalid json-valid');
                    $card.find('.bg-amber-100, .bg-green-100').removeClass('bg-green-100 text-green-800').addClass(
                        'bg-amber-100 text-amber-800');
                }
            } catch (e) {
                $icon.removeClass('text-gray-400 text-green-500').addClass('text-red-400');
                $textarea.addClass('json-invalid').removeClass('json-valid');
                $card.find('.bg-amber-100, .bg-green-100').removeClass('bg-green-100 text-green-800').addClass(
                    'bg-red-100 text-red-800');
            }
        });

        // Save all settings in a category
        $(document).on('click', '.save-category-btn', function() {
            const $btn = $(this);
            const categoryId = $btn.data('category-id');
            const $inputs = $(`.setting-input[data-category-id="${categoryId}"]`);
            const categoryName = $btn.find('span').text().replace('Save ', '').replace(' Settings', '');

            // Collect all settings data for this category
            const settings = [];
            let hasError = false;

            $inputs.each(function() {
                const $input = $(this);
                const settingKey = $input.data('setting-key');
                const settingType = $input.data('setting-type');

                let value;
                if (settingType === 'boolean') {
                    value = $input.is(':checked') ? '1' : '0';
                } else {
                    value = $input.val();
                }

                // Validate JSON if type is json
                if (settingType === 'json' && value) {
                    try {
                        JSON.parse(value);
                    } catch (e) {
                        toastr.error('Invalid JSON format in one of the fields');
                        $input.addClass('json-invalid');
                        hasError = true;
                        return false;
                    }
                }

                settings.push({
                    key: settingKey,
                    value: value,
                    type: settingType,
                    category_id: categoryId
                });
            });

            if (hasError) return;

            const originalText = $btn.html();
            $btn.prop('disabled', true).html(`
                <i class="fas fa-spinner fa-spin"></i>
                <span>Saving...</span>
            `);

            $.ajax({
                url: '{{ route('admin.settings.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    settings: settings
                },
                success: function(response) {
                    $btn.prop('disabled', false).html(originalText);
                    if (response.success) {
                        toastr.success(response.message ||
                            `${categoryName} settings updated successfully`);

                        // Visual feedback for all updated cards
                        $inputs.each(function() {
                            const $card = $(this).closest('.setting-card');
                            $card.addClass('flash-success');

                            // Special effect for toggle switches
                            if ($(this).data('setting-type') === 'boolean') {
                                const $toggle = $card.find('.peer-checked\\:from-sky-500');
                                $toggle.addClass('animate-pulse');
                                setTimeout(() => {
                                    $toggle.removeClass('animate-pulse');
                                }, 1000);
                            }

                            setTimeout(() => {
                                $card.removeClass('flash-success');
                            }, 1000);
                        });
                    } else {
                        toastr.error(response.message || 'Failed to update settings');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalText);
                    const errorMsg = xhr.responseJSON?.message || 'Server Error. Please try again.';
                    toastr.error(errorMsg);

                    // Show error state
                    $inputs.each(function() {
                        $(this).addClass('border-red-300');
                        setTimeout(() => {
                            $(this).removeClass('border-red-300');
                        }, 3000);
                    });
                }
            });
        });

        // Initialize JSON validation on load
        $(document).ready(function() {
            $('.setting-input[data-setting-type="json"]').trigger('input');

            // Make sidebar sticky on larger screens
            if (window.innerWidth >= 1024) {
                $('.lg\\:w-64').css('position', 'sticky').css('top', '6rem');
            }

            // Validate appointment_booking_days - prevent 0 or negative values
            function validateBookingDays() {
                const $input = $('[data-setting-key="appointment_booking_days"]');
                if ($input.length === 0) return; // Field not present on this page

                const value = parseInt($input.val());

                if (value <= 0 || isNaN(value)) {
                    $input.addClass('border-red-500');
                    $input.siblings('.error-hint').remove();
                    $input.after(
                        '<p class="text-xs text-red-500 mt-1 error-hint"> Must be at least 1. Negative or zero values are not allowed.</p>'
                    );
                    return false;
                } else {
                    $input.removeClass('border-red-500');
                    $input.siblings('.error-hint').remove();
                    return true;
                }
            }

            $('[data-setting-key="appointment_booking_days"]').on('change input', validateBookingDays);

            // Trigger validation on load
            $('[data-setting-key="appointment_booking_days"]').trigger('change');
        });
    </script>
@endpush
