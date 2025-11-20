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
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" id="first_name" required pattern="[A-Za-z\s]{2,100}"
                            title="First name should only contain letters and spaces (2-100 characters)"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="first_name_error" class="text-xs text-red-500 hidden">First name should only contain
                            letters</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" required pattern="[A-Za-z\s]{2,100}"
                            title="Last name should only contain letters and spaces (2-100 characters)"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="last_name_error" class="text-xs text-red-500 hidden">Last name should only contain
                            letters</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="email_error" class="text-xs text-red-500 hidden">Please enter a valid email address</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel" name="phone" id="phone" required pattern="[0-9]{10,15}"
                            title="Phone number must be 10-15 digits only" maxlength="15"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <span id="phone_error" class="text-xs text-red-500 hidden">Phone must be 10-15 digits only</span>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required
                            max="{{ now()->subDay()->format('Y-m-d') }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <select name="gender" id="gender" required
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


                    <!-- Appointment fields (kept outside patientForm so hiding patientForm won't hide them) -->
                    <div id="appointmentFields" class="mt-4">
                        <!-- Doctor Selection -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
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

                        <!-- Date & Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mt-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date
                                    *</label>
                                <input type="date" name="appointment_date" id="appointment_date" required
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time
                                    *</label>
                                <input type="time" name="appointment_time" id="appointment_time" required
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            </div>
                        </div>

                        <!-- Appointment Type -->
                        <div class="mt-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Type
                                *</label>
                            <select id="type_select" name="appointment_type"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="">Select type...</option>
                                <option value="consultation">Consultation</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="emergency">Emergency</option>
                                <option value="check_up">Check Up</option>
                            </select>
                        </div>

                        <!-- Reason -->
                        <div class="mt-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit
                                *</label>
                            <textarea name="reason_for_visit" id="reason_for_visit" rows="3" required
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"></textarea>
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                                placeholder="Enter any additional notes..."></textarea>
                        </div>

                        <!-- Payment Status -->
                        <div class="mt-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                            <select name="payment_status" id="payment_status"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
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
                    } else {
                        $('#patientForm').show();
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
