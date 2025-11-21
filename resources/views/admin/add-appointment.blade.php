@extends('layouts.admin')

@section('title', 'Add Appointment')

@section('page-title', 'Add New Appointment')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
            <form class="space-y-4 sm:space-y-6" action="{{ route('admin.store-appointment') }}" method="post"
                id="appointmentForm" enctype="multipart/form-data">
                @csrf
                <!-- Patient Selection -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Patient</label>
                    <select id="select_patient" name="patient_id"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                        <option value="">Search or select patient... (or leave empty to create new)</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} -
                                {{ $patient->phone ?? '' }}</option>
                        @endforeach
                    </select>

                </div>


                <!-- Patient Form - Only patient fields are inside this -->
                <div id="patientForm"
                    class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mt-4 p-3 sm:p-4 bg-white rounded-lg border border-gray-200">
                    <div class="col-span-1 md:col-span-2">
                        <p class="text-xs sm:text-sm font-medium text-gray-800 mb-3">Patient Details</p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">First Name <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="first_name" id="first_name" pattern="[A-Za-z\s]{2,100}"
                            placeholder="Enter first name"
                            title="First name should only contain letters and spaces (2-100 characters)"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="first_name_error" class="text-xs text-red-500 hidden">First name should only contain
                            letters</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Last Name <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="last_name" id="last_name" pattern="[A-Za-z\s]{2,100}"
                            placeholder="Enter last name"
                            title="Last name should only contain letters and spaces (2-100 characters)"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="last_name_error" class="text-xs text-red-500 hidden">Last name should only contain
                            letters</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Email <span
                                class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email" placeholder="Enter email"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="email_error" class="text-xs text-red-500 hidden">Please enter a valid email address</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Phone <span
                                class="text-red-600">*</span></label>
                        <input type="tel" name="phone" id="phone" pattern="[0-9]{10,15}"
                            placeholder="Enter phone number" title="Phone number must be 10-15 digits only" maxlength="15"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="phone_error" class="text-xs text-red-500 hidden">Phone must be 10-15 digits only</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Date of Birth <span
                                class="text-red-600">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            max="{{ now()->subDay()->format('Y-m-d') }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Gender <span
                                class="text-red-600">*</span></label>
                        <select name="gender" id="gender"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="2"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                            placeholder="Enter full address..."></textarea>
                    </div>
                </div>

                <!-- Appointment Details Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Appointment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <!-- Doctor Selection -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor <span
                                    class="text-red-600">*</span></label>
                            <select id="doctor_select" name="doctor_id"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                                <option value="">Search or select doctor...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}
                                        @if ($doctor->doctorProfile && $doctor->doctorProfile->specialty)
                                            - {{ $doctor->doctorProfile->specialty->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date
                                <span class="text-red-600">*</span></label>
                            <input type="date" name="appointment_date" id="appointment_date" min="{{ date('Y-m-d') }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time
                                <span class="text-red-600">*</span></label>
                            <select name="appointment_time" id="appointment_time"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="">Select date first</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Type
                                <span class="text-red-600">*</span></label>
                            <select id="type_select" name="appointment_type"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="">Select type...</option>
                                <option value="consultation">Consultation</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="emergency">Emergency</option>
                                <option value="check_up">Check Up</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status <span
                                    class="text-red-600">*</span></label>
                            <select name="status" id="status"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                            </select>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit
                                <span class="text-red-600">*</span></label>
                            <textarea name="reason_for_visit" id="reason_for_visit" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                                placeholder="Enter reason for visit..."></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                                placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-4 pt-4">
                    <a href="{{ route('admin.appointments') }}"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 text-center">Cancel</a>
                    <button type="submit"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">Create
                        Appointment</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Toggle patient details form when a patient is selected
                function togglePatientForm() {
                    var pid = $('#select_patient').val();
                    if (pid && pid !== '') {
                        $('#patientForm').hide();
                        // Remove required attribute from hidden fields to prevent validation errors
                        $('#patientForm input, #patientForm select, #patientForm textarea').prop('required', false);
                    } else {
                        $('#patientForm').show();
                        // Add back required attribute when showing the form
                        $('#first_name, #last_name, #email, #phone, #date_of_birth, #gender').prop('required', true);
                    }
                }

                // Initialize visibility
                togglePatientForm();

                // When selection changes, toggle form
                $('#select_patient').on('change', function() {
                    togglePatientForm();
                });

                // Initialize Select2 for doctor select with clear option
                $('#doctor_select').select2({
                    placeholder: 'Search or select doctor...',
                    allowClear: false,
                    width: '100%'
                });

                // Initialize Select2 for type select with clear option
                $('#type_select').select2({
                    placeholder: 'Select type...',
                    allowClear: false,
                    width: '100%'
                });

                // Initialize Select2 for patient select (allow clearing to show create form)
                $('#select_patient').select2({
                    placeholder: 'Search or select patient...',
                    allowClear: true,
                    width: '100%'
                });

                // When user clicks "Create new patient" show the patient form and clear selection
                $('#createNewPatientBtn').on('click', function() {
                    $('#select_patient').val(null).trigger('change');
                    $('#patientForm').show();
                });

                // Load available time slots when doctor and date are selected
                function loadAvailableSlots() {
                    const doctorId = $('#doctor_select').val();
                    const date = $('#appointment_date').val();
                    const timeSelect = $('#appointment_time');

                    if (!doctorId || !date) {
                        timeSelect.html('<option value="">Select doctor and date first</option>');
                        return;
                    }

                    timeSelect.html('<option value="">Loading slots...</option>').prop('disabled', true);

                    $.ajax({
                        url: '{{ route('admin.get-available-slots') }}',
                        method: 'GET',
                        data: {
                            doctor_id: doctorId,
                            date: date
                        },
                        success: function(response) {
                            timeSelect.prop('disabled', false);
                            if (response.success && response.slots && response.slots.length > 0) {
                                let options = '<option value="">Select Time</option>';
                                const selectedDate = new Date(date);
                                const today = new Date();
                                const isToday = selectedDate.toDateString() === today.toDateString();
                                const currentTime = today.getHours() * 60 + today.getMinutes();

                                response.slots.forEach(function(slot) {
                                    let isPast = false;

                                    if (isToday) {
                                        // Parse time from slot (format: "HH:MM AM/PM" or "HH:MM")
                                        const timeMatch = slot.match(
                                            /(\d{1,2}):(\d{2})\s*(AM|PM)?/i);
                                        if (timeMatch) {
                                            let hours = parseInt(timeMatch[1]);
                                            const minutes = parseInt(timeMatch[2]);
                                            const meridiem = timeMatch[3];

                                            // Convert to 24-hour format if AM/PM present
                                            if (meridiem) {
                                                if (meridiem.toUpperCase() === 'PM' && hours !==
                                                    12) {
                                                    hours += 12;
                                                } else if (meridiem.toUpperCase() === 'AM' &&
                                                    hours === 12) {
                                                    hours = 0;
                                                }
                                            }

                                            const slotTime = hours * 60 + minutes;
                                            isPast = slotTime <= currentTime;
                                        }
                                    }

                                    if (!isPast) {
                                        options += `<option value="${slot}">${slot}</option>`;
                                    }
                                });

                                if (options === '<option value="">Select Time</option>') {
                                    timeSelect.html(
                                        '<option value="">No available slots remaining for today</option>'
                                    );
                                } else {
                                    timeSelect.html(options);
                                }
                            } else {
                                timeSelect.html(
                                    '<option value="">No slots available for this date</option>');
                            }
                        },
                        error: function() {
                            timeSelect.html('<option value="">Error loading slots</option>').prop(
                                'disabled', false);
                        }
                    });
                }

                // Trigger slot loading when doctor or date changes
                $('#doctor_select, #appointment_date').on('change', function() {
                    loadAvailableSlots();
                });

                $('#appointmentForm').on('submit', function(e) {
                    e.preventDefault();

                    // When user selected existing patient, form includes patient_id.
                    // When no patient selected, include patient form fields so backend can create patient.
                    var formData = new FormData(this);

                    // Show loading state
                    const submitBtn = $(this).find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

                    $.ajax({
                        url: $(this).attr('action'),
                        data: formData,
                        type: $(this).attr('method') || 'POST',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            try {
                                if (response.status == 200) {
                                    toastr.success(response.msg);
                                    setTimeout(function() {
                                        window.location.href =
                                            "{{ route('admin.appointments') }}";
                                    }, 200);
                                } else {
                                    toastr.error(response.msg);
                                    submitBtn.prop('disabled', false).html(originalText);
                                }
                            } catch (e) {
                                toastr.error("An error occurred while processing the response.");
                                console.error(e);
                                submitBtn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function(xhr, status, error) {
                            try {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    Object.keys(errors).forEach(function(key) {
                                        toastr.error(errors[key][0]);
                                    });
                                } else {
                                    toastr.error("An error occurred: " + error);
                                }
                            } catch (e) {
                                toastr.error("A server error occurred.");
                                console.error(e);
                            }
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
