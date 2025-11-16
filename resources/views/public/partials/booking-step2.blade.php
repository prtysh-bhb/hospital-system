    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <h1 class="text-xl sm:text-2xl font-bold text-sky-700">MediCare Hospital</h1>
            <p class="text-xs sm:text-sm text-gray-600">Book Your Appointment</p>
        </div>
    </header>

    <!-- Progress Steps -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">✓</div>
                <span class="text-xs sm:text-xs text-sky-600 font-medium">Doctor</span>
            </div>
            <div class="flex-1 h-1 bg-sky-600 mx-1 sm:mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-sky-600 text-white rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">2</div>
                <span class="text-xs sm:text-xs text-sky-600 font-medium text-center">Date & Time</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">3</div>
                <span class="text-xs sm:text-xs text-gray-500 text-center">Details</span>
            </div>
            <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">4</div>
                <span class="text-xs sm:text-xs text-gray-500 text-center">Confirm</span>
            </div>
        </div>

        <!-- Selected Doctor Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <h3 class="text-sm font-medium text-gray-500 mb-3 sm:mb-4">Selected Doctor</h3>
            <div class="flex items-center space-x-3 sm:space-x-4">
                <img src="https://ui-avatars.com/api/?name=Dr+Rajesh+Sharma&background=0ea5e9&color=fff&size=80" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg" alt="Doctor">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Dr. Rajesh Sharma</h3>
                    <p class="text-xs sm:text-sm text-sky-600">Cardiologist</p>
                    <p class="text-xs text-gray-600">Consultation Fee: ₹800</p>
                </div>
            </div>
        </div>

        <!-- STEP 2: Date & Time Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 sm:mb-6">Select Date & Time</h2>

            <!-- Calendar -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:mb-3">Select Date</label>
                <div class="grid grid-cols-7 gap-1 sm:gap-2">
                    <!-- Calendar Header -->
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>

                    <!-- Calendar Days -->
                    <button class="p-2 sm:p-3 text-gray-400 text-xs sm:text-sm">28</button>
                    <button class="p-2 sm:p-3 text-gray-400 text-xs sm:text-sm">29</button>
                    <button class="p-2 sm:p-3 text-gray-400 text-xs sm:text-sm">30</button>
                    <button class="p-2 sm:p-3 text-gray-400 text-xs sm:text-sm">31</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">1</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">2</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">3</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">4</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">5</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">6</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">7</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">8</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">9</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">10</button>
                    <button class="p-2 sm:p-3 bg-sky-600 text-white text-xs sm:text-sm rounded-lg font-semibold">11</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">12</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">13</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">14</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">15</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">16</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">17</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">18</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">19</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">20</button>
                    <button class="p-2 sm:p-3 text-gray-800 text-xs sm:text-sm hover:bg-sky-50 rounded-lg">21</button>
                </div>
            </div>

            <!-- Time Slots -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Available Time Slots</label>
                <div class="grid grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">09:00 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">09:30 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-400 rounded-lg text-xs sm:text-sm cursor-not-allowed" disabled>10:00 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">10:30 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 bg-sky-600 text-white rounded-lg text-xs sm:text-sm font-semibold">11:00 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">11:30 AM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">02:00 PM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">02:30 PM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-400 rounded-lg text-xs sm:text-sm cursor-not-allowed" disabled>03:00 PM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">03:30 PM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">04:00 PM</button>
                    <button class="px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg text-xs sm:text-sm hover:border-sky-600 hover:text-sky-600">04:30 PM</button>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between mb-6">
            <a href="{{ route('booking', ['step' => 1]) }}" class="px-6 sm:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-lg text-sm sm:text-base font-medium hover:bg-gray-300">Back</a>
            <a href="{{ route('booking', ['step' => 3]) }}" class="px-6 sm:px-8 py-2.5 sm:py-3 bg-sky-600 text-white rounded-lg text-sm sm:text-base font-medium hover:bg-sky-700">Next Step</a>
        </div>
    </div>
