<div id="appointment-booking-modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-6">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 relative max-h-[95vh] overflow-y-auto">
        <!-- Close Button -->
        <button
            class="close-appointment-booking absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <h3 class="text-xl font-semibold text-gray-800 mb-6">Appointment Booking</h3>
        <hr>

        <form class="space-y-6" id="appointmentForm" action="{{ route('patient.store.appointment') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="patient_id" value="{{ auth()->user()->id }}">

            <!-- Doctor & Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Doctor <span
                            class="text-red-600">*</span></label>
                    <select id="doctor_select" name="doctor_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm">
                        <option value="">Search or select doctor...</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}
                                @if ($doctor->doctorProfile && $doctor->doctorProfile->specialty)
                                    - {{ $doctor->doctorProfile->specialty->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <span id="doctor_id_error" class="text-xs text-red-500 hidden"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Type <span
                            class="text-red-600">*</span></label>
                    <select id="type_select" name="appointment_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300 text-sm">
                        <option value="">Select type...</option>
                        <option value="consultation">Consultation</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="emergency">Emergency</option>
                        <option value="check_up">Check Up</option>
                    </select>
                    <span id="appointment_type_error" class="text-xs text-red-500 hidden"></span>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date <span
                            class="text-red-600">*</span></label>
                    <input type="date" name="appointment_date" id="appointment_date" min="{{ date('Y-m-d') }}"
                        max="{{ date('Y-m-d', strtotime("+{$advanceBookingDays} days")) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300 text-sm">
                    <span id="appointment_date_error" class="text-xs text-red-500 hidden"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Time <span
                            class="text-red-600">*</span></label>
                    <select name="appointment_time" id="appointment_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300 text-sm">
                        <option value="">Select date first</option>
                    </select>
                    <span id="appointment_time_error" class="text-xs text-red-500 hidden"></span>
                </div>
            </div>

            <!-- Reason & Notes -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Visit <span
                            class="text-red-600">*</span></label>
                    <textarea name="reason_for_visit" id="reason_for_visit" rows="3" maxlength="1000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-0 focus:border-gray-300"
                        placeholder="Enter reason for visit..."></textarea>
                    <div class="flex justify-between mt-1">
                        <span id="reason_for_visit_error" class="text-xs text-red-500 hidden"></span>
                        <span class="text-xs text-gray-400"><span id="reason_count">0</span>/1000</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="2" maxlength="500"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-gray-300 text-sm"
                        placeholder="Enter any additional notes..."></textarea>
                    <div class="flex justify-between mt-1">
                        <span id="notes_error" class="text-xs text-red-500 hidden"></span>
                        <span class="text-xs text-gray-400"><span id="notes_count">0</span>/500</span>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                <a
                    class="close-appointment-booking cursor-pointer px-6 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 text-center">Cancel</a>
                <button type="submit" id="submit-appointment-btn"
                    class="px-6 py-2 text-sm bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors">Create
                    Appointment</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Character Counters ==========
        const reasonForVisit = document.getElementById('reason_for_visit');
        const reasonCount = document.getElementById('reason_count');
        reasonForVisit.addEventListener('input', function() {
            reasonCount.textContent = reasonForVisit.value.length;
        });

        const notes = document.getElementById('notes');
        const notesCount = document.getElementById('notes_count');
        notes.addEventListener('input', function() {
            notesCount.textContent = notes.value.length;
        });

        // ========== Fetch Available Times ==========
        const doctorSelect = document.getElementById('doctor_select');
        const appointmentDate = document.getElementById('appointment_date');
        const appointmentTime = document.getElementById('appointment_time');

        function fetchAvailableTimes() {
            const doctorId = doctorSelect.value;
            const date = appointmentDate.value;

            if (doctorId && date) {
                appointmentTime.innerHTML = '<option value="">Loading...</option>';
                appointmentTime.disabled = true;

                fetch(`/api/available-times?doctor_id=${doctorId}&date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        appointmentTime.innerHTML = '<option value="">Select time...</option>';
                        if (data && data.length > 0) {
                            data.forEach(time => {
                                const option = document.createElement('option');
                                // Convert to 24-hour format if in 12-hour format
                                const timeValue = time.includes('AM') || time.includes('PM') ?
                                    convertTo24Hour(time) :
                                    time;
                                option.value = timeValue;
                                option.textContent = time;
                                appointmentTime.appendChild(option);
                            });
                        } else {
                            appointmentTime.innerHTML = '<option value="">No slots available</option>';
                        }
                        appointmentTime.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching available times:', error);
                        appointmentTime.innerHTML = '<option value="">Error loading times</option>';
                        appointmentTime.disabled = false;
                        toastr.error('Failed to load available times');
                    });
            } else {
                appointmentTime.innerHTML = '<option value="">Select date first</option>';
                appointmentTime.disabled = true;
            }
        }

        // Helper function to convert 12-hour to 24-hour format
        function convertTo24Hour(time12) {
            const [time, period] = time12.split(' ');
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours);

            if (period === 'AM') {
                if (hours === 12) hours = 0;
            } else {
                if (hours !== 12) hours += 12;
            }

            return `${String(hours).padStart(2, '0')}:${minutes}`;
        }

        doctorSelect.addEventListener('change', fetchAvailableTimes);
        appointmentDate.addEventListener('change', fetchAvailableTimes);

        // ========== Form Validation & Error Handling ==========
        function clearFieldError(fieldName) {
            const errorElement = document.getElementById(`${fieldName}_error`);
            const field = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);

            if (field) {
                field.classList.remove('border-red-500');
            }
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
        }

        function showFieldError(fieldName, message) {
            const errorElement = document.getElementById(`${fieldName}_error`);
            const field = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);

            if (field) {
                field.classList.add('border-red-500');
            }
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }

        function clearAllErrors() {
            const errorElements = document.querySelectorAll('[id$="_error"]');
            errorElements.forEach(elem => {
                elem.classList.add('hidden');
                elem.textContent = '';
            });
            const fields = document.querySelectorAll('[name]');
            fields.forEach(field => {
                field.classList.remove('border-red-500');
            });
        }

        // ========== Form Validation ==========
        function validateForm() {
            clearAllErrors();
            let isValid = true;

            const doctorId = document.getElementById('doctor_select').value;
            const appointmentType = document.getElementById('type_select').value;
            const appointmentDateValue = document.getElementById('appointment_date').value;
            const appointmentTimeValue = document.getElementById('appointment_time').value;
            const reasonForVisitValue = document.getElementById('reason_for_visit').value.trim();

            if (!doctorId) {
                showFieldError('doctor_id', 'Please select a doctor');
                isValid = false;
            }

            if (!appointmentType) {
                showFieldError('appointment_type', 'Please select appointment type');
                isValid = false;
            }

            if (!appointmentDateValue) {
                showFieldError('appointment_date', 'Please select a date');
                isValid = false;
            }

            if (!appointmentTimeValue) {
                showFieldError('appointment_time', 'Please select a time');
                isValid = false;
            }

            if (!reasonForVisitValue) {
                showFieldError('reason_for_visit', 'Please enter reason for visit');
                isValid = false;
            } else if (reasonForVisitValue.length < 10) {
                showFieldError('reason_for_visit', 'Reason must be at least 10 characters');
                isValid = false;
            } else if (reasonForVisitValue.length > 1000) {
                showFieldError('reason_for_visit', 'Reason cannot exceed 1000 characters');
                isValid = false;
            }

            return isValid;
        }

        // ========== Form Submission with AJAX ==========
        const appointmentForm = document.getElementById('appointmentForm');
        const submitBtn = document.getElementById('submit-appointment-btn');

        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!validateForm()) {
                return;
            }

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<svg class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...';

            // Prepare form data
            const formData = new FormData(this);

            // Send AJAX request
            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.status === 200) {
                        toastr.success(data.message || 'Appointment created successfully!',
                            'Success', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 3000
                            });

                        // Close modal and reset form
                        document.getElementById('appointment-booking-modal').classList.add(
                            'hidden');
                        appointmentForm.reset();
                        clearAllErrors();
                        reasonCount.textContent = '0';
                        notesCount.textContent = '0';
                        appointmentTime.innerHTML = '<option value="">Select date first</option>';

                    } else {
                        toastr.error(data.message || 'Failed to create appointment', 'Error', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000
                        });
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;

                        // Display validation errors if any
                        if (data.errors && typeof data.errors === 'object') {
                            Object.keys(data.errors).forEach(fieldName => {
                                const errorMessage = Array.isArray(data.errors[fieldName]) ?
                                    data.errors[fieldName][0] :
                                    data.errors[fieldName];
                                showFieldError(fieldName, errorMessage);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error(
                        'An error occurred while creating the appointment. Please try again.',
                        'Error', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000
                        });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Clear error on field change
        document.querySelectorAll('[name], [id$="_select"], [id$="_date"], [id$="_time"]').forEach(field => {
            field.addEventListener('change', function() {
                const fieldName = this.name || this.id.replace(/_select|_date|_time$/, '');
                clearFieldError(fieldName);
            });
            field.addEventListener('input', function() {
                const fieldName = this.name || this.id.replace(/_select|_date|_time$/, '');
                clearFieldError(fieldName);
            });
        });

        // Close modal handlers
        document.querySelectorAll('.close-appointment-booking').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('appointment-booking-modal').classList.add('hidden');
            });
        });
    });
</script>
