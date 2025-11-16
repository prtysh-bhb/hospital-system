@extends('layouts.admin')

@section('title', 'Add Doctor')

@section('page-title', 'Add New Doctor')

@section('content')
<form class="max-w-4xl mx-auto">
    <!-- Personal Details -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Personal Details</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" placeholder="Dr. John Doe" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" placeholder="doctor@hospital.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                <input type="tel" placeholder="+91 98765 43210" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>O+</option>
                    <option>O-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <textarea rows="3" placeholder="Complete address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
            </div>
        </div>
    </div>

    <!-- Professional Details -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Professional Details</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Specialty *</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option>Cardiologist</option>
                    <option>Pediatrician</option>
                    <option>Orthopedic</option>
                    <option>Dermatologist</option>
                    <option>Neurologist</option>
                    <option>General Physician</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option>Cardiology</option>
                    <option>Pediatrics</option>
                    <option>Orthopedics</option>
                    <option>Dermatology</option>
                    <option>Neurology</option>
                    <option>General Medicine</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Qualification *</label>
                <input type="text" placeholder="MBBS, MD" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Experience (Years) *</label>
                <input type="number" placeholder="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                <input type="text" placeholder="MCI12345" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee (₹) *</label>
                <input type="number" placeholder="800" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slot Duration (Minutes) *</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option>15</option>
                    <option>30</option>
                    <option>45</option>
                    <option>60</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken</label>
                <input type="text" placeholder="English, Hindi, Marathi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Availability Schedule -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 sm:mb-6">Availability Schedule</h3>

        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <input type="checkbox" id="monday" class="w-5 h-5 text-sky-600 rounded">
                <label for="monday" class="w-32 text-sm font-medium text-gray-700">Monday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="17:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="tuesday" class="w-5 h-5 text-sky-600 rounded">
                <label for="tuesday" class="w-32 text-sm font-medium text-gray-700">Tuesday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="17:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="wednesday" class="w-5 h-5 text-sky-600 rounded">
                <label for="wednesday" class="w-32 text-sm font-medium text-gray-700">Wednesday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="17:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="thursday" class="w-5 h-5 text-sky-600 rounded">
                <label for="thursday" class="w-32 text-sm font-medium text-gray-700">Thursday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="17:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="friday" class="w-5 h-5 text-sky-600 rounded">
                <label for="friday" class="w-32 text-sm font-medium text-gray-700">Friday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="17:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="saturday" class="w-5 h-5 text-sky-600 rounded">
                <label for="saturday" class="w-32 text-sm font-medium text-gray-700">Saturday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="13:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center space-x-4">
                <input type="checkbox" id="sunday" class="w-5 h-5 text-sky-600 rounded">
                <label for="sunday" class="w-32 text-sm font-medium text-gray-700">Sunday</label>
                <input type="time" value="09:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                <span class="text-gray-500">to</span>
                <input type="time" value="13:00" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500">
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:space-x-4">
        <button type="button" class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-center">Cancel</button>
        <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base text-white bg-sky-600 hover:bg-sky-700 rounded-lg font-medium">Add Doctor</button>
    </div>
</form>
@endsection
