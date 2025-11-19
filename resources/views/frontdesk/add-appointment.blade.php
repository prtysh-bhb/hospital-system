@extends('layouts.frontdesk')

@section('title', 'Add Appointment')

@section('page-title', 'Add New Appointment')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form id="appointmentForm" class="space-y-4 sm:space-y-6">
            @csrf
            <!-- Patient Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Patient Information</h3>

                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search Existing Patient</label>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" id="patientSearch" placeholder="Search by name, email, or phone..."
                            class="flex-1 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                        <button type="button" id="searchBtn"
                            class="px-4 sm:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                            Search
                        </button>
                    </div>
                    <!-- Search Results -->
                    <div id="searchResults" class="mt-3 hidden"></div>
                </div>

                <!-- Selected Patient Info -->
                <div id="selectedPatientInfo" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg hidden">
                    <p class="text-sm font-medium text-green-800">Selected Patient: <span id="selectedPatientName"></span>
                    </p>
                    <input type="hidden" id="patient_id" name="patient_id">
                    <button type="button" onclick="clearPatientSelection()"
                        class="mt-2 text-xs text-red-600 hover:text-red-800">
                        Clear Selection
                    </button>
                </div>

                <!-- Patient Form - Always Visible -->
                <div id="patientForm"
                    class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mt-4 p-3 sm:p-4 bg-white rounded-lg border border-gray-200">
                    <div class="col-span-1 md:col-span-2">
                        <p class="text-xs sm:text-sm font-medium text-gray-800 mb-3">Patient Details</p>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" id="first_name" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel" name="phone" id="phone" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required
                            max="{{ date('Y-m-d', strtotime('-1 day')) }}"
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
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Appointment Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                        <select name="doctor_id" id="doctor_id" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            <option value="">Loading doctors...</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Specialty</label>
                        <input type="text" id="specialty" readonly
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg text-sm sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                        <input type="date" name="appointment_date" id="appointment_date" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                        <select name="appointment_time" id="appointment_time" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            <option value="">Select date first</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Type *</label>
                        <select name="appointment_type" id="appointment_type" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            <option value="">Select Type</option>
                            <option value="consultation">Consultation</option>
                            <option value="follow_up">Follow-up</option>
                            <option value="emergency">Emergency</option>
                            <option value="check_up">General Checkup</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                        <input type="text" id="consultation_fee" readonly
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg text-sm sm:text-base">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                        <textarea name="reason_for_visit" id="reason_for_visit" required rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                            placeholder="Enter reason for visit..."></textarea>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                            placeholder="Any additional notes or special requirements..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4">
                <button type="button" onclick="window.location.href='{{ route('frontdesk.dashboard') }}'"
                    class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm sm:text-base">
                    Cancel
                </button>
                <button type="submit" id="submitBtn"
                    class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 text-sm sm:text-base">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Appointment Booked Successfully!</h3>
                <p class="text-sm text-gray-500 mb-4">Appointment Number: <span id="appointmentNumber"
                        class="font-semibold"></span></p>
                <button onclick="window.location.href='{{ route('frontdesk.dashboard') }}'"
                    class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                    Back to Dashboard
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedPatientId = null;
        let doctorsData = [];
        let patients = [];

        // Load doctors on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDoctors();

            // Add event listeners
            document.getElementById('doctor_id').addEventListener('change', updateDoctorDetails);
            document.getElementById('appointment_date').addEventListener('change', loadAvailableSlots);
            document.getElementById('searchBtn').addEventListener('click', searchPatients);
            document.getElementById('patientSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchPatients();
                }
            });

            // Form submission
            document.getElementById('appointmentForm').addEventListener('submit', handleFormSubmit);
        });

        // Load doctors from API
        function loadDoctors() {
            fetch('{{ route('frontdesk.add-appointment.doctors') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        doctorsData = data.doctors;
                        const select = document.getElementById('doctor_id');
                        select.innerHTML = '<option value="">Choose a doctor</option>';

                        data.doctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.id;
                            option.textContent = `${doctor.name} - ${doctor.specialty}`;
                            option.setAttribute('data-specialty', doctor.specialty);
                            option.setAttribute('data-fee', doctor.fee || 0);
                            select.appendChild(option);
                        });
                    } else {
                        console.error('Failed to load doctors:', data.message);
                        document.getElementById('doctor_id').innerHTML =
                            '<option value="">Error loading doctors</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading doctors:', error);
                    document.getElementById('doctor_id').innerHTML =
                        '<option value="">Error loading doctors. Please refresh.</option>';
                });
        }

        // Update specialty and fee when doctor selected
        function updateDoctorDetails() {
            const select = document.getElementById('doctor_id');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                document.getElementById('specialty').value = selectedOption.getAttribute('data-specialty') || '';
                const fee = selectedOption.getAttribute('data-fee') || 0;
                document.getElementById('consultation_fee').value = fee > 0 ? `$${parseFloat(fee).toFixed(2)}` : 'Free';
            } else {
                document.getElementById('specialty').value = '';
                document.getElementById('consultation_fee').value = '';
            }

            // Load available slots if date is selected
            const date = document.getElementById('appointment_date').value;
            if (date && selectedOption.value) {
                loadAvailableSlots();
            }
        }

        // Load available slots when date changes
        function loadAvailableSlots() {
            const doctorId = document.getElementById('doctor_id').value;
            const date = document.getElementById('appointment_date').value;
            const timeSelect = document.getElementById('appointment_time');

            if (!doctorId || !date) {
                timeSelect.innerHTML = '<option value="">Select doctor and date first</option>';
                return;
            }

            timeSelect.innerHTML = '<option value="">Loading slots...</option>';
            timeSelect.disabled = true;

            fetch(`{{ route('frontdesk.add-appointment.available-slots') }}?doctor_id=${doctorId}&date=${date}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    timeSelect.disabled = false;

                    if (data.success && data.slots && data.slots.length > 0) {
                        timeSelect.innerHTML = '<option value="">Select Time</option>';
                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot;
                            option.textContent = slot;
                            timeSelect.appendChild(option);
                        });
                    } else {
                        timeSelect.innerHTML = '<option value="">No slots available for this date</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading slots:', error);
                    timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                    timeSelect.disabled = false;
                });
        }

        function searchPatients() {
            const search = document.getElementById('patientSearch').value.trim();
            if (search.length < 2) {
                alert('Please enter at least 2 characters');
                return;
            }

            fetch(`{{ route('frontdesk.add-appointment.search-patient') }}?search=${encodeURIComponent(search)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displaySearchResults(data.patients);
                    } else {
                        console.error('Search failed:', data.message);
                        document.getElementById('searchResults').innerHTML =
                            '<p class="text-sm text-red-500 p-2">Search failed</p>';
                        document.getElementById('searchResults').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error searching patients:', error);
                    document.getElementById('searchResults').innerHTML =
                        '<p class="text-sm text-red-500 p-2">Error searching patients</p>';
                    document.getElementById('searchResults').classList.remove('hidden');
                });
        }

        function displaySearchResults(patientsData) {
            const resultsDiv = document.getElementById('searchResults');
            patients = patientsData || [];

            if (patients.length === 0) {
                resultsDiv.innerHTML = '<p class="text-sm text-gray-500 p-2">No patients found</p>';
                resultsDiv.classList.remove('hidden');
                return;
            }

            resultsDiv.innerHTML = patients.map(patient => `
                <div class="p-3 border border-gray-200 rounded-lg mb-2 hover:bg-gray-50 cursor-pointer" onclick="selectPatient(${patient.id})">
                    <p class="text-sm font-medium">${patient.name || 'Unknown'}</p>
                    <p class="text-xs text-gray-500">${patient.email || 'No email'} | ${patient.phone || 'No phone'}</p>
                </div>
            `).join('');
            resultsDiv.classList.remove('hidden');
        }

        function selectPatient(id) {
            const patient = patients.find(p => p.id === id);
            if (!patient) return;

            selectedPatientId = id;
            document.getElementById('patient_id').value = id;
            document.getElementById('selectedPatientName').textContent = patient.name || 'Unknown Patient';
            document.getElementById('selectedPatientInfo').classList.remove('hidden');
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('patientSearch').value = '';

            // Split name into first and last name
            const nameParts = (patient.name || '').split(' ');
            const firstName = nameParts[0] || '';
            const lastName = nameParts.slice(1).join(' ') || '';

            // Fill patient details
            document.getElementById('first_name').value = firstName;
            document.getElementById('last_name').value = lastName;
            document.getElementById('email').value = patient.email || '';
            document.getElementById('phone').value = patient.phone || '';

            if (patient.date_of_birth) {
                document.getElementById('date_of_birth').value = patient.date_of_birth;
            }
            if (patient.gender) {
                document.getElementById('gender').value = patient.gender;
            }

            // Disable fields when existing patient is selected
            const fieldsToDisable = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender'];
            fieldsToDisable.forEach(field => {
                const element = document.getElementById(field);
                if (element) element.disabled = true;
            });
        }

        function clearPatientSelection() {
            selectedPatientId = null;
            document.getElementById('patient_id').value = '';
            document.getElementById('selectedPatientInfo').classList.add('hidden');

            // Clear and enable all fields
            const fieldsToClear = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'address'];
            fieldsToClear.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    element.value = '';
                    element.disabled = false;
                }
            });
        }

        // Form submission
        function handleFormSubmit(e) {
            e.preventDefault();

            // If no patient selected, validate the patient form fields
            if (!selectedPatientId) {
                const patientFields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender'];
                let hasErrors = false;

                patientFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.classList.add('border-red-500');
                        hasErrors = true;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                if (hasErrors) {
                    alert('Please fill in all required patient fields (marked with *)');
                    return;
                }
            }

            // Validate appointment fields
            const appointmentFields = ['doctor_id', 'appointment_date', 'appointment_time', 'appointment_type',
                'reason_for_visit'
            ];
            let appointmentErrors = false;

            appointmentFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value) {
                    input.classList.add('border-red-500');
                    appointmentErrors = true;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (appointmentErrors) {
                alert('Please fill in all required appointment fields');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Booking...';

            const formData = new FormData(this);

            fetch('{{ route('frontdesk.add-appointment.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('appointmentNumber').textContent = data.appointment_number;
                        document.getElementById('successModal').classList.remove('hidden');
                    } else {
                        alert(data.message || 'Failed to book appointment');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Book Appointment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while booking the appointment');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Book Appointment';
                });
        }
    </script>
@endpush
