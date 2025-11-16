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
                <div class="w-10 h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-2">1</div>
                <span class="text-xs text-sky-600 font-medium">Doctor</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-2">2</div>
                <span class="text-xs text-gray-500">Date & Time</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-2">3</div>
                <span class="text-xs text-gray-500">Details</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-2">4</div>
                <span class="text-xs text-gray-500">Confirm</span>
            </div>
        </div>

        <!-- STEP 1: Doctor Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Select a Doctor</h2>

            <!-- Specialty Filter -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Filter by Specialty</label>
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm font-medium">All</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:border-sky-600 hover:text-sky-600">Cardiologist</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:border-sky-600 hover:text-sky-600">Pediatrician</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:border-sky-600 hover:text-sky-600">Orthopedic</button>
                </div>
            </div>

            <!-- Doctor Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-xl p-4 hover:border-sky-600 hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-start space-x-4">
                        <img src="https://ui-avatars.com/api/?name=Dr+Rajesh+Sharma&background=0ea5e9&color=fff&size=80" class="w-20 h-20 rounded-lg" alt="Doctor">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Dr. Rajesh Sharma</h3>
                            <p class="text-sm text-sky-600 mb-2">Cardiologist</p>
                            <p class="text-xs text-gray-600 mb-1">MBBS, MD (Cardiology)</p>
                            <p class="text-xs text-gray-600 mb-2">12 years experience</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-800">₹800</span>
                                <span class="text-xs text-green-600 font-medium">Available Today</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl p-4 hover:border-sky-600 hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-start space-x-4">
                        <img src="https://ui-avatars.com/api/?name=Dr+Priya+Mehta&background=8b5cf6&color=fff&size=80" class="w-20 h-20 rounded-lg" alt="Doctor">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">Dr. Priya Mehta</h3>
                            <p class="text-sm text-purple-600 mb-2">Pediatrician</p>
                            <p class="text-xs text-gray-600 mb-1">MBBS, MD (Pediatrics)</p>
                            <p class="text-xs text-gray-600 mb-2">8 years experience</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-800">₹600</span>
                                <span class="text-xs text-green-600 font-medium">Available Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-end">
            <a href="{{ route('booking', ['step' => 2]) }}" class="px-8 py-3 bg-sky-600 text-white rounded-lg font-medium hover:bg-sky-700">Next Step</a>
        </div>
    </div>
