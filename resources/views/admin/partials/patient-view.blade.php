@php
    $user = $patient->user;
    $age = $user?->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : null;
    $initials = $user ? strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) : '?';
    $lastVisit = $patient->appointments()->latest()->first();
@endphp

<div class="space-y-6">
    <!-- Patient Header -->
    <div class="flex items-center space-x-4 pb-6 border-b border-gray-200">
        <div class="w-20 h-20 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center font-bold text-2xl">
            {{ $initials }}
        </div>
        <div>
            @if ($user?->full_name)
                <h4 class="text-2xl font-bold text-gray-800">{{ $user->full_name }}</h4>
            @endif
            <p class="text-sm text-gray-500">Patient ID: #PT{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <!-- Patient Information Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Personal Information -->
        <div class="space-y-4">
            <h5 class="text-lg font-semibold text-gray-800 mb-3">Personal Information</h5>

            @if ($user?->email)
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-800">{{ $user->email }}</p>
                </div>
            @endif

            @if ($user?->phone)
                <div>
                    <label class="text-sm font-medium text-gray-600">Phone</label>
                    <p class="text-gray-800">{{ $user->phone }}</p>
                </div>
            @endif

            @if ($user?->date_of_birth)
                <div>
                    <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                    <p class="text-gray-800">
                        {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') }}
                        @if ($age)
                            <span class="text-sm text-gray-500">({{ $age }} years)</span>
                        @endif
                    </p>
                </div>
            @endif

            @if ($user?->gender)
                <div>
                    <label class="text-sm font-medium text-gray-600">Gender</label>
                    <p class="text-gray-800">{{ ucfirst($user->gender) }}</p>
                </div>
            @endif

            @if ($patient->blood_group)
                <div>
                    <label class="text-sm font-medium text-gray-600">Blood Group</label>
                    <p class="text-gray-800">
                        <span class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                            {{ $patient->blood_group }}
                        </span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Additional Information -->
        <div class="space-y-4">
            <h5 class="text-lg font-semibold text-gray-800 mb-3">Additional Information</h5>

            @if ($user?->status)
                <div>
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <p>
                        <span
                            class="px-3 py-1 text-xs font-medium rounded-full
                        @if ($user->status === 'active') bg-green-100 text-green-700
                        @elseif($user->status === 'inactive') bg-gray-100 text-gray-700
                        @else bg-amber-100 text-amber-700 @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                </div>
            @endif

            @if ($user?->address)
                <div>
                    <label class="text-sm font-medium text-gray-600">Address</label>
                    <p class="text-gray-800">{{ $user->address }}</p>
                </div>
            @endif

            @if ($patient->emergency_contact_name)
                <div>
                    <label class="text-sm font-medium text-gray-600">Emergency Contact Name</label>
                    <p class="text-gray-800">{{ $patient->emergency_contact_name }}</p>
                </div>
            @endif

            @if ($patient->emergency_contact_phone)
                <div>
                    <label class="text-sm font-medium text-gray-600">Emergency Contact Phone</label>
                    <p class="text-gray-800">{{ $patient->emergency_contact_phone }}</p>
                </div>
            @endif

            @if ($patient->created_at)
                <div>
                    <label class="text-sm font-medium text-gray-600">Registered On</label>
                    <p class="text-gray-800">{{ $patient->created_at->addHours(1)->format('d M Y, h:i A') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Medical Information -->
    @if ($patient->medical_history || $patient->current_medications)
        <div class="pt-6 border-t border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800 mb-3">Medical Information</h5>

            @if ($patient->medical_history)
                <div>
                    <label class="text-sm font-medium text-gray-600">Medical History</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1">
                        <p class="text-sm text-gray-700">{{ $patient->medical_history }}</p>
                    </div>
                </div>
            @endif

            @if ($patient->current_medications)
                <div>
                    <label class="text-sm font-medium text-gray-600">Current Medications</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1">
                        {{-- Table or text display for medications --}}
                        <p class="text-sm text-gray-700">{{ $patient->current_medications }}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Insurance Information -->
    @if ($patient->insurance_provider || $patient->insurance_number)
        <div class="pt-6 border-t border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800 mb-3">Insurance Information</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if ($patient->insurance_provider)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Insurance Provider</label>
                        <p class="text-gray-800">{{ $patient->insurance_provider }}</p>
                    </div>
                @endif

                @if ($patient->insurance_number)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Insurance Number</label>
                        <p class="text-gray-800">{{ $patient->insurance_number }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Appointments / Medical History -->
    @if ($patient->appointments && $patient->appointments->count() > 0)
        <div class="pt-6 border-t border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800 mb-3">Appointment History</h5>
            <p class="text-sm text-gray-600">Total Appointments: {{ $patient->appointments->count() }}</p>
            <p class="text-sm text-gray-600">Last Visit: {{ $lastVisit?->appointment_date?->format('d M Y') ?? 'N/A' }}
            </p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
        <button onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
            Close
        </button>
        <button onclick="closeModal(); editPatient({{ $patient->id }})"
            class="px-4 py-2 text-white bg-sky-600 rounded-lg hover:bg-sky-700">
            Edit Patient
        </button>
    </div>
</div>
