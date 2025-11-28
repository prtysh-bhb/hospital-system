<form id="editAppointmentForm" class="space-y-4 sm:space-y-6">
    @csrf
    <input type="hidden" id="appointment_id" name="appointment_id" value="{{ $appointment->id ?? '' }}">

    <!-- Patient Selection -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
        <select id="edit_select_patient" name="patient_id"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
            <option>Search or select patient...</option>
            @foreach ($patients as $patient)
                <option value="{{ $patient->id }}"
                    {{ $appointment && $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Doctor Selection -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
        <select id="edit_doctor_select" name="doctor_id"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
            <option>Search or select doctor...</option>
            @foreach ($doctors as $doctor)
                <option value="{{ $doctor->id }}"
                    {{ $appointment && $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->first_name }} {{ $doctor->last_name }} -
                    {{ $doctor->doctorProfile->specialty->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Date & Time -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
            <input type="date" id="edit_appointment_date" name="appointment_date"
                value="{{ $appointment ? $appointment->appointment_date->format('Y-m-d') : '' }}"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
            <input type="time" id="edit_appointment_time" name="appointment_time"
                value="{{ $appointment ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '' }}"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
        </div>
    </div>

    <!-- Appointment Type -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Type *</label>
        <select id="edit_type_select" name="appointment_type"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
            <option>Select type...</option>
            <option value="consultation"
                {{ $appointment && $appointment->appointment_type == 'consultation' ? 'selected' : '' }}>Consultation
            </option>
            <option value="follow_up"
                {{ $appointment && $appointment->appointment_type == 'follow_up' ? 'selected' : '' }}>Follow-up
            </option>
            <option value="emergency"
                {{ $appointment && $appointment->appointment_type == 'emergency' ? 'selected' : '' }}>Emergency
            </option>
            <option value="check_up"
                {{ $appointment && $appointment->appointment_type == 'check_up' ? 'selected' : '' }}>Check-up</option>
        </select>
    </div>

    <!-- Reason -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
        <textarea id="edit_reason_for_visit" name="reason_for_visit"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent"
            rows="4" placeholder="Enter reason for visit or symptoms">{{ $appointment ? $appointment->reason_for_visit : '' }}</textarea>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
        <textarea id="edit_notes" name="notes"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent"
            rows="3" placeholder="Any additional information (optional)">{{ $appointment ? $appointment->notes : '' }}</textarea>
    </div>

    <!-- Status -->
    <div>
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status</label>
        <select id="edit_status" name="status"
            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
            <option value="pending" {{ $appointment && $appointment->status == 'pending' ? 'selected' : '' }}>Pending
            </option>
            <option value="confirmed" {{ $appointment && $appointment->status == 'confirmed' ? 'selected' : '' }}>
                Confirmed</option>
            <option value="checked_in" {{ $appointment && $appointment->status == 'checked_in' ? 'selected' : '' }}>
                Checked In</option>
            <option value="in_progress" {{ $appointment && $appointment->status == 'in_progress' ? 'selected' : '' }}>
                In Progress</option>
            <option value="completed" {{ $appointment && $appointment->status == 'completed' ? 'selected' : '' }}>
                Completed</option>
            <option value="cancelled" {{ $appointment && $appointment->status == 'cancelled' ? 'selected' : '' }}>
                Cancelled</option>
            <option value="no_show" {{ $appointment && $appointment->status == 'no_show' ? 'selected' : '' }}>No Show
            </option>
        </select>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()"
            class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
            Cancel
        </button>
        <button type="submit" class="px-6 py-2 text-white bg-sky-600 rounded-lg hover:bg-sky-700">
            Save Changes
        </button>
    </div>
</form>
