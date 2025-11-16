    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-sky-700">MediCare Hospital</h1>
            <p class="text-sm text-gray-600">Book Your Appointment</p>
        </div>
    </header>

    <!-- Progress Steps -->
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-2">✓</div>
                <span class="text-xs text-sky-600 font-medium">Doctor</span>
            </div>
            <div class="flex-1 h-1 bg-sky-600 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-2">✓</div>
                <span class="text-xs text-sky-600 font-medium">Date & Time</span>
            </div>
            <div class="flex-1 h-1 bg-sky-600 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-2">3</div>
                <span class="text-xs text-sky-600 font-medium">Details</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-2">4</div>
                <span class="text-xs text-gray-500">Confirm</span>
            </div>
        </div>

        <!-- Appointment Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-medium text-gray-500 mb-4">Appointment Summary</h3>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="https://ui-avatars.com/api/?name=Dr+Rajesh+Sharma&background=0ea5e9&color=fff&size=80" class="w-16 h-16 rounded-lg" alt="Doctor">
                    <div>
                        <h3 class="font-semibold text-gray-800">Dr. Rajesh Sharma</h3>
                        <p class="text-sm text-sky-600">Cardiologist</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">December 11, 2024</p>
                    <p class="text-sm font-semibold text-gray-800">11:00 AM</p>
                </div>
            </div>
        </div>

        <!-- STEP 3: Patient Details Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Patient Details</h2>

            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" placeholder="Enter first name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" placeholder="Enter last name">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" placeholder="Enter email">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" placeholder="Enter phone number">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent">
                            <option>Select gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" rows="3" placeholder="Enter address"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit *</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" rows="3" placeholder="Describe your symptoms or reason for visit"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you have any allergies?</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-600 focus:border-transparent" placeholder="List any allergies (optional)">
                </div>
            </form>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between mb-6">
            <a href="{{ route('booking', ['step' => 2]) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300">Back</a>
            <a href="{{ route('booking', ['step' => 4]) }}" class="px-6 py-3 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">Review & Confirm</a>
        </div>
    </div>
