@extends('layouts.admin')

@section('title', 'Add Appointment')

@section('page-title', 'Add New Appointment')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
        <form class="space-y-4 sm:space-y-6">
            <!-- Patient Selection -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                <select class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                    <option>Search or select patient...</option>
                    <option>Amit Patel - #P001</option>
                    <option>Priya Sharma - #P002</option>
                    <option>Rahul Kumar - #P003</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Or <a href="{{ route('admin.patients') }}" class="text-sky-600 hover:underline">create new patient</a></p>
            </div>

            <!-- Doctor Selection -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                <select class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                    <option>Search or select doctor...</option>
                    <option>Dr. Rajesh Sharma - Cardiologist</option>
                    <option>Dr. Anjali Verma - Pediatrician</option>
                    <option>Dr. Vikram Singh - Orthopedic</option>
                    <option>Dr. Neha Gupta - Dermatologist</option>
                </select>
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                    <input type="date" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Time *</label>
                    <input type="time" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                </div>
            </div>

            <!-- Appointment Type -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Appointment Type *</label>
                <select class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                    <option>Select type...</option>
                    <option>Consultation</option>
                    <option>Follow-up</option>
                    <option>Check-up</option>
                    <option>Emergency</option>
                </select>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                <textarea class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" rows="4" placeholder="Enter reason for visit or symptoms"></textarea>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" rows="3" placeholder="Any additional information (optional)"></textarea>
            </div>

            <!-- Payment Status -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                    <option>Pending</option>
                    <option>Paid</option>
                    <option>Partial</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-4 pt-4">
                <a href="{{ route('admin.appointments') }}" class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 text-center">Cancel</a>
                <button type="submit" class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">Create Appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection
