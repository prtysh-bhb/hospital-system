@extends('layouts.admin')

@section('title', isset($doctor) ? 'Edit Doctor' : 'Add Doctor')

@section('page-title', isset($doctor) ? 'Edit Doctor' : 'Add New Doctor')

@section('content')
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($doctor) ? route('admin.doctor-update', $doctor->user->id) : route('admin.doctor-store') }}"
        method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto">
        @csrf
        @if (isset($doctor))
            @method('PUT')
        @endif
        <!-- Personal Details -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Personal Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $doctor->user->first_name ?? '') }}"
                        required placeholder="John"
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $doctor->user->last_name ?? '') }}"
                        required placeholder="Doe"
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="gender" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $doctor->user->gender ?? '') == 'male' ? 'selected' : '' }}>
                            Male</option>
                        <option value="female"
                            {{ old('gender', $doctor->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"
                            {{ old('gender', $doctor->user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $doctor->user->email ?? '') }}" required
                        placeholder="doctor@hospital.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *
                        {{ !isset($doctor) ? '(will be used as password)' : '' }}</label>
                    <input type="tel" name="phone" value="{{ old('phone', $doctor->user->phone ?? '') }}" required
                        placeholder="+91 98765 43210"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input type="date" name="date_of_birth"
                        value="{{ old('date_of_birth', isset($doctor) && $doctor->user->date_of_birth && strtotime($doctor->user->date_of_birth) ? date('Y-m-d', strtotime($doctor->user->date_of_birth)) : '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                @if (isset($doctor))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                            <option value="active"
                                {{ old('status', $doctor->user->status ?? '') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive"
                                {{ old('status', $doctor->user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Available for Booking *</label>
                        <select name="available_for_booking" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                            <option value="1"
                                {{ old('available_for_booking', $doctor->available_for_booking ?? 1) == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('available_for_booking', $doctor->available_for_booking ?? 1) == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <textarea name="address" required rows="3" placeholder="Complete address"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">{{ old('address', $doctor->user->address ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    @if (isset($doctor) && $doctor->user->profile_image)
                        <div class="mb-2">
                            <img src="{{ asset($doctor->user->profile_image) }}" alt="Current Photo"
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                            <p class="text-xs text-gray-500 mt-1">Current photo</p>
                        </div>
                    @endif
                    <input type="file" name="profile_image" accept="image/jpeg,image/jpg,image/png"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, JPEG, PNG (Max:
                        2MB){{ isset($doctor) ? '. Leave empty to keep current photo.' : '' }}</p>
                </div>

            </div>
        </div>

        <!-- Professional Details -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Professional Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specialty *</label>
                    <select name="specialty_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Select Specialty</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                {{ old('specialty_id', $doctor->specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Qualification *</label>
                    <input type="text" name="qualification"
                        value="{{ old('qualification', $doctor->qualification ?? '') }}" required placeholder="MBBS, MD"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience (Years) *</label>
                    <input type="number" name="experience_years"
                        value="{{ old('experience_years', $doctor->experience_years ?? '') }}" required min="0"
                        placeholder="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                    <input type="text" name="license_number"
                        value="{{ old('license_number', $doctor->license_number ?? '') }}" required placeholder="MCI12345"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee (₹) *</label>
                    <input type="number" name="consultation_fee"
                        value="{{ old('consultation_fee', $doctor->consultation_fee ?? '') }}" required min="0"
                        step="0.01" placeholder="800"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slot Duration (Minutes) *</label>
                    <select name="slot_duration" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        @php
                            $currentSlotDuration = isset($doctor)
                                ? $doctor->user->doctorSchedules->first()->slot_duration ?? 30
                                : 30;
                        @endphp
                        <option value="15" {{ old('slot_duration', $currentSlotDuration) == 15 ? 'selected' : '' }}>15
                        </option>
                        <option value="30" {{ old('slot_duration', $currentSlotDuration) == 30 ? 'selected' : '' }}>30
                        </option>
                        <option value="45" {{ old('slot_duration', $currentSlotDuration) == 45 ? 'selected' : '' }}>45
                        </option>
                        <option value="60" {{ old('slot_duration', $currentSlotDuration) == 60 ? 'selected' : '' }}>60
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken</label>
                    <input type="text" name="languages" value="{{ old('languages', $doctor->bio ?? '') }}"
                        placeholder="English, Hindi, Marathi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Availability Schedule -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Availability Schedule</h3>

            <div class="space-y-4">
                @php
                    $days = [
                        0 => 'Sunday',
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                    ];

                    // Create a map of existing schedules if editing
                    $existingSchedules = [];
                    if (isset($doctor)) {
                        foreach ($doctor->user->doctorSchedules as $schedule) {
                            $existingSchedules[$schedule->day_of_week] = $schedule;
                        }
                    }
                @endphp

                @foreach ($days as $dayNum => $dayName)
                    @php
                        $existingSchedule = $existingSchedules[$dayNum] ?? null;
                        $isChecked = $existingSchedule && $existingSchedule->is_available;
                        $startTime = $existingSchedule
                            ? \Carbon\Carbon::parse($existingSchedule->start_time)->format('H:i')
                            : '09:00';
                        $endTime = $existingSchedule
                            ? \Carbon\Carbon::parse($existingSchedule->end_time)->format('H:i')
                            : ($dayNum == 6 || $dayNum == 0
                                ? '13:00'
                                : '17:00');
                    @endphp
                    <div class="flex items-center space-x-4">
                        <input type="checkbox" name="schedules[{{ $dayNum }}][enabled]"
                            id="day{{ $dayNum }}" value="1" {{ $isChecked ? 'checked' : '' }}
                            class="w-5 h-5 text-sky-600 rounded">
                        <label for="day{{ $dayNum }}"
                            class="w-32 text-sm font-medium text-gray-700">{{ $dayName }}</label>
                        <input type="time" name="schedules[{{ $dayNum }}][start_time]"
                            value="{{ $startTime }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <span class="text-gray-500">to</span>
                        <input type="time" name="schedules[{{ $dayNum }}][end_time]"
                            value="{{ $endTime }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-4">
            <a href="{{ route('admin.doctors') }}"
                class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-center">Cancel</a>
            <button type="submit"
                class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">
                {{ isset($doctor) ? 'Update Doctor' : 'Add Doctor' }}
            </button>
        </div>
    </form>
@endsection
