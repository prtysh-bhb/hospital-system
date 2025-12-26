<div id="appointment-booking-modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6 relative max-h-[90vh] overflow-y-auto">
        <button
            class="close-appointment-booking absolute top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Appointment Booking</h3>
        <div id="appointment-booking-content">
            <form class="space-y-4 sm:space-y-6" action="{{ route('patient.store.appointment') }}" method="post"
                id="appointmentForm" enctype="multipart/form-data">
                @csrf
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        @php
                            $user = auth()->user();
                            $patient_id = $user->id;
                        @endphp
                        <input type="hidden" name="patient_id" value="{{ $patient_id }}">

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor <span
                                    class="text-red-600">*</span></label>
                            <select id="doctor_select" name="doctor_id"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                                <option value="">Search or select doctor...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }}
                                        {{ $doctor->last_name }}
                                        @if ($doctor->doctorProfile && $doctor->doctorProfile->specialty)
                                            - {{ $doctor->doctorProfile->specialty->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <span id="doctor_id_error" class="text-xs text-red-500 hidden"></span>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date
                                <span class="text-red-600">*</span></label>
                            <input type="date" name="appointment_date" id="appointment_date"
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                            <span id="appointment_date_error" class="text-xs text-red-500 hidden"></span>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time
                                <span class="text-red-600">*</span></label>
                            <select name="appointment_time" id="appointment_time"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base">
                                <option value="">Select date first</option>
                            </select>
                            <span id="appointment_time_error" class="text-xs text-red-500 hidden"></span>
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
                            <span id="appointment_type_error" class="text-xs text-red-500 hidden"></span>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit
                                <span class="text-red-600">*</span></label>
                            <textarea name="reason_for_visit" id="reason_for_visit" rows="3" maxlength="1000"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                                placeholder="Enter reason for visit..."></textarea>
                            <span id="reason_for_visit_error" class="text-xs text-red-500 hidden"></span>
                            <span class="text-xs text-gray-400"><span id="reason_count">0</span>/1000 characters</span>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional
                                Notes</label>
                            <textarea name="notes" id="notes" rows="2" maxlength="500"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 text-sm sm:text-base"
                                placeholder="Enter any additional notes..."></textarea>
                            <span id="notes_error" class="text-xs text-red-500 hidden"></span>
                            <span class="text-xs text-gray-400"><span id="notes_count">0</span>/500 characters</span>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-4 pt-4">
                    <a
                        class="close-appointment-booking cursor-pointer px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 text-center">Cancel</a>
                    <button type="submit" id="submitBtn"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">Create
                        Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
