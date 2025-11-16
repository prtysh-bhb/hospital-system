@extends('layouts.frontdesk')

@section('title', 'Add Appointment')

@section('page-title', 'Add New Appointment')

@section('content')
<div class="max-w-4xl mx-auto">
    <form class="space-y-6">
        <!-- Patient Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Patient Information</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Existing Patient</label>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Search by name, email, or phone..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    <button type="button" class="px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                        Search
                    </button>
                </div>
            </div>

            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-sm text-gray-500">OR</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <div class="mb-4">
                <button type="button" class="px-4 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50">
                    + Add New Patient
                </button>
            </div>

            <!-- New Patient Form (Initially hidden, shown when "Add New Patient" is clicked) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 p-4 bg-sky-50 rounded-lg border border-sky-200">
                <div class="col-span-2">
                    <p class="text-sm font-medium text-sky-800 mb-3">New Patient Details</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Appointment Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                    <select required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Choose a doctor</option>
                        <option value="dr-johnson">Dr. Sarah Johnson - Cardiologist</option>
                        <option value="dr-smith">Dr. Michael Smith - Pediatrician</option>
                        <option value="dr-williams">Dr. Emily Williams - Orthopedic</option>
                        <option value="dr-brown">Dr. David Brown - Dermatologist</option>
                        <option value="dr-davis">Dr. Jennifer Davis - Neurologist</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specialty</label>
                    <input type="text" value="Cardiologist" readonly
                           class="w-full px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                    <input type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                    <select required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Select Time</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="14:00">02:00 PM</option>
                        <option value="14:30">02:30 PM</option>
                        <option value="15:00">03:00 PM</option>
                        <option value="15:30">03:30 PM</option>
                        <option value="16:00">04:00 PM</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Type *</label>
                    <select required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Select Type</option>
                        <option value="consultation">Consultation</option>
                        <option value="follow-up">Follow-up</option>
                        <option value="emergency">Emergency</option>
                        <option value="checkup">General Checkup</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                    <input type="text" value="$150.00" readonly
                           class="w-full px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                    <textarea required rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500"
                              placeholder="Enter reason for visit..."></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500"
                              placeholder="Any additional notes or special requirements..."></textarea>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                    <select required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Select Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="insurance">Insurance</option>
                        <option value="online">Online Payment</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="text" value="$150.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                    <input type="text" placeholder="Optional"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-4">
            <button type="button" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button type="button" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Save as Draft
            </button>
            <button type="submit" class="px-6 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                Book Appointment
            </button>
        </div>
    </form>
</div>
@endsection
