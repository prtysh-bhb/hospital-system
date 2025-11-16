@extends('layouts.public')

@section('title', 'Booking Confirmed')

@section('body-class', 'bg-gray-50 min-h-screen')

@section('content')
  <!-- Header -->
  <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-sky-600 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-bold text-gray-900">City General Hospital</h1>
            <p class="text-xs text-gray-500">Book Your Appointment</p>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Progress Bar -->
  <div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-600 text-white font-semibold">
            ✓
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">Select Doctor</p>
          </div>
        </div>
        <div class="flex-1 h-0.5 bg-sky-600 mx-4"></div>

        <div class="flex items-center">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-600 text-white font-semibold">
            ✓
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">Date & Time</p>
          </div>
        </div>
        <div class="flex-1 h-0.5 bg-sky-600 mx-4"></div>

        <div class="flex items-center">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-600 text-white font-semibold">
            ✓
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">Patient Details</p>
          </div>
        </div>
        <div class="flex-1 h-0.5 bg-sky-600 mx-4"></div>

        <div class="flex items-center">
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-sky-600 text-white font-semibold">
            4
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-sky-600">Confirmation</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Success Message -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
      <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 text-center border-b border-gray-200">
        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Appointment Confirmed!</h2>
        <p class="text-gray-600">Your appointment has been successfully booked</p>
      </div>

      <!-- Appointment ID -->
      <div class="bg-sky-50 p-6 border-b border-gray-200">
        <div class="text-center">
          <p class="text-sm text-gray-600 mb-2">Your Appointment ID</p>
          <div class="text-4xl font-bold text-sky-600 tracking-wider mb-2">APT-2024-001234</div>
          <p class="text-xs text-gray-500">Please save this ID for your records</p>
        </div>
      </div>

      <!-- Appointment Details -->
      <div class="p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Appointment Details</h3>

        <div class="space-y-4">
          <!-- Doctor Info -->
          <div class="flex items-start p-4 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center text-sky-600 font-semibold text-xl">
              DR
            </div>
            <div class="ml-4 flex-1">
              <div class="flex items-start justify-between">
                <div>
                  <p class="font-semibold text-gray-900">Dr. Sarah Johnson</p>
                  <p class="text-sm text-gray-600">Cardiologist</p>
                  <p class="text-xs text-gray-500 mt-1">MBBS, MD (Cardiology) • 15 years exp.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Date & Time -->
          <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Date</p>
                  <p class="font-semibold text-gray-900">Dec 25, 2024</p>
                  <p class="text-xs text-gray-600">Wednesday</p>
                </div>
              </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-500">Time</p>
                  <p class="font-semibold text-gray-900">10:00 AM</p>
                  <p class="text-xs text-gray-600">30 min slot</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Patient Details -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500 mb-3">Patient Information</p>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500">Name</p>
                <p class="font-medium text-gray-900">Rajesh Kumar</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Mobile</p>
                <p class="font-medium text-gray-900">+91 98765 43210</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Age</p>
                <p class="font-medium text-gray-900">35 years</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Gender</p>
                <p class="font-medium text-gray-900">Male</p>
              </div>
            </div>
            <div class="mt-3">
              <p class="text-xs text-gray-500">Chief Complaint</p>
              <p class="font-medium text-gray-900">Chest pain and irregular heartbeat</p>
            </div>
          </div>

          <!-- Appointment Type -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-500">Appointment Type</p>
                <p class="font-semibold text-gray-900">New Patient</p>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-500">Consultation Fee</p>
                <p class="font-semibold text-sky-600 text-lg">₹800</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Important Instructions -->
      <div class="bg-amber-50 border-t border-amber-100 p-6">
        <h4 class="font-semibold text-amber-900 mb-3 flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
          </svg>
          Important Instructions
        </h4>
        <ul class="space-y-2 text-sm text-amber-900">
          <li class="flex items-start">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-2 mr-2"></span>
            <span>Please arrive 15 minutes before your scheduled appointment time</span>
          </li>
          <li class="flex items-start">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-2 mr-2"></span>
            <span>Show this Appointment ID at the reception desk</span>
          </li>
          <li class="flex items-start">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-2 mr-2"></span>
            <span>Bring any previous medical records or prescriptions</span>
          </li>
          <li class="flex items-start">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-2 mr-2"></span>
            <span>For cancellation or rescheduling, call: +91 99999 88888</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      <button class="flex items-center justify-center px-6 py-3 bg-white border-2 border-sky-600 text-sky-600 font-semibold rounded-lg hover:bg-sky-50 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download PDF
      </button>

      <button class="flex items-center justify-center px-6 py-3 bg-white border-2 border-sky-600 text-sky-600 font-semibold rounded-lg hover:bg-sky-50 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
      </button>

      <button class="flex items-center justify-center px-6 py-3 bg-white border-2 border-sky-600 text-sky-600 font-semibold rounded-lg hover:bg-sky-50 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        Share via SMS
      </button>
    </div>

    <!-- Add to Calendar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
      <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Add to Your Calendar
      </h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <button class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
          Google Calendar
        </button>
        <button class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
          Apple Calendar
        </button>
        <button class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
          Outlook
        </button>
        <button class="flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
          Yahoo
        </button>
      </div>
    </div>

    <!-- Navigation -->
    <div class="flex items-center justify-between">
      <a href="{{ route('booking.step1') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
        Book Another Appointment
      </a>
      <a href="#" class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition-colors">
        Go to Homepage
      </a>
    </div>

  </main>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="text-center text-sm text-gray-500">
        <p>&copy; 2024 City General Hospital. All rights reserved.</p>
        <p class="mt-1">For support, call: +91 99999 88888 | Email: support@cityhospital.com</p>
      </div>
    </div>
  </footer>
@endsection
