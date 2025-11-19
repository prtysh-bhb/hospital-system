    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <h1 class="text-xl sm:text-2xl font-bold text-sky-700">MediCare Hospital</h1>
            <p class="text-xs sm:text-sm text-gray-600">Book Your Appointment</p>
        </div>
    </header>

    <!-- Progress Steps -->
    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="step" value="3">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        ✓</div>
                    <span class="text-xs sm:text-xs text-sky-600 font-medium">Doctor</span>
                </div>
                <div class="flex-1 h-1 bg-sky-600 mx-1 sm:mx-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        ✓</div>
                    <span class="text-xs sm:text-xs text-sky-600 font-medium text-center">Date & Time</span>
                </div>
                <div class="flex-1 h-1 bg-sky-600 mx-1 sm:mx-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        3</div>
                    <span class="text-xs sm:text-xs text-sky-600 font-medium">Details</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        4</div>
                    <span class="text-xs sm:text-xs text-gray-500 text-center">Confirm</span>
                </div>
            </div>

            <!-- Appointment Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3 sm:mb-4">Appointment Summary</h3>
                <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->first_name . ' ' . $doctor->last_name) }}&background=0ea5e9&color=fff&size=80"
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg" alt="Doctor">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $doctor->first_name }}</h3>
                            <p class="text-xs sm:text-sm text-sky-600">
                                {{ $doctor->doctorProfile->specialty->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800">{{ $selectedSlot }}</p>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Patient Details Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 sm:mb-6">Patient Details</h2>

                <form class="space-y-3 sm:space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                                placeholder="Enter first name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                                placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                                placeholder="Enter email">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                                placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                            <input type="date"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm">
                                <option>Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                            rows="3" placeholder="Enter address"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                        <textarea
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                            rows="3" placeholder="Describe your symptoms or reason for visit"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Do you have any allergies?</label>
                        <input type="text"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent text-sm"
                            placeholder="List any allergies (optional)">
                    </div>
                </form>
            </div>

            <!-- Navigation -->
            <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 sm:gap-0 mb-6">
                <a href="{{ route('booking', ['step' => 2]) }}"
                    class="px-6 sm:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-lg text-sm sm:text-base font-medium hover:bg-gray-300 text-center">Back</a>
                <a href="{{ route('booking', ['step' => 4]) }}"
                    class="px-6 sm:px-8 py-2.5 sm:py-3 bg-sky-600 text-white rounded-lg text-sm sm:text-base font-medium hover:bg-sky-700 text-center">Review
                    & Confirm</a>
            </div>
        </div>
    </form>
