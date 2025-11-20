    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <h1 class="text-xl sm:text-2xl font-bold text-sky-700">MediCare Hospital</h1>
            <p class="text-xs sm:text-sm text-gray-600">Book Your Appointment</p>
        </div>
    </header>

    <!-- Progress Steps -->

    <form action="{{ route('booking.store') }}" method="POST" id="step2Form">
        @csrf
        <input type="hidden" name="step" value="2">
        <input type="hidden" name="date" id="selectedDateInput" value="{{ $selectedDate }}">
        <input type="hidden" name="slot" id="selectedSlotInput" value="">

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
                        2</div>
                    <span class="text-xs sm:text-xs text-sky-600 font-medium text-center">Date & Time</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        3</div>
                    <span class="text-xs sm:text-xs text-gray-500 text-center">Details</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-1 sm:mx-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-semibold mb-1 sm:mb-2 text-sm sm:text-base">
                        4</div>
                    <span class="text-xs sm:text-xs text-gray-500 text-center">Confirm</span>
                </div>
            </div>

            {{-- Selected Doctor Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3 sm:mb-4">Selected Doctor</h3>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->first_name . ' ' . $doctor->last_name) }}&background={{ $doctor->doctorProfile->specialty->color ?? '0ea5e9' }}&color=fff"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg">

                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">
                            {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </h3>
                        <p class="text-xs sm:text-sm text-sky-600">
                            {{ $doctor->doctorProfile->specialty->name ?? 'NA' }}
                        </p>
                        <p class="text-xs text-gray-600">
                            Consultation Fee: ₹{{ $doctor->doctorProfile->consultation_fee ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>


            {{-- SELECT DATE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">

                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 sm:mb-6">Select Date</h2>

                {{-- CALENDAR HEADER --}}
                <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                    <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>
                </div>

                {{-- CALENDAR DAYS --}}
                @php
                    $today = \Carbon\Carbon::today()->format('Y-m-d'); // Today's date
                @endphp

                <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-6">

                    @foreach ($calendar as $day)
                        @php
                            $classes =
                                'calendar-day p-2 sm:p-3 rounded-lg text-xs sm:text-sm text-center transition-colors';

                            $isPast = $day['date'] < $today; // disable past dates
                            $isSelected = $day['date'] === $selectedDate;
                            $isToday = $day['date'] === $today;

                            if ($isPast) {
                                $classes .= ' text-gray-300 bg-gray-100 cursor-not-allowed';
                            } elseif ($isSelected) {
                                $classes .= ' bg-sky-600 text-white font-semibold shadow-md';
                            } elseif ($isToday) {
                                $classes .=
                                    ' bg-sky-100 text-sky-800 font-semibold cursor-pointer border-2 border-sky-600';
                            } else {
                                $classes .= ' text-gray-800 hover:bg-sky-50 cursor-pointer border border-transparent';
                            }
                        @endphp

                        @if (!$isPast)
                            <div class="{{ $classes }}" data-date="{{ $day['date'] }}">
                                {{ $day['day'] }}
                            </div>
                        @else
                            <div class="{{ $classes }}">{{ $day['day'] }}</div>
                        @endif
                    @endforeach

                </div>


                {{-- TIME SLOTS --}}
                <label class="block text-sm font-medium text-gray-700 mb-3">Available Time Slots</label>

                @php
                    $now = \Carbon\Carbon::now();
                    $isToday = $selectedDate === \Carbon\Carbon::today()->format('Y-m-d');
                    $currentTime = $now->hour * 60 + $now->minute;
                    $availableSlots = [];

                    foreach ($slots as $slot) {
                        $isPast = false;

                        if ($isToday) {
                            // Parse time from slot
                            if (preg_match('/(\d{1,2}):(\d{2})\s*(AM|PM)?/i', $slot, $matches)) {
                                $hours = (int) $matches[1];
                                $minutes = (int) $matches[2];
                                $meridiem = $matches[3] ?? null;

                                // Convert to 24-hour format if AM/PM present
                                if ($meridiem) {
                                    if (strtoupper($meridiem) === 'PM' && $hours !== 12) {
                                        $hours += 12;
                                    } elseif (strtoupper($meridiem) === 'AM' && $hours === 12) {
                                        $hours = 0;
                                    }
                                }

                                $slotTime = $hours * 60 + $minutes;
                                $isPast = $slotTime <= $currentTime;
                            }
                        }

                        if (!$isPast) {
                            $availableSlots[] = $slot;
                        }
                    }
                @endphp

                <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
                    @forelse ($availableSlots as $slot)
                        <label class="cursor-pointer slot-option">
                            <input type="radio" name="slot" value="{{ $slot }}"
                                class="hidden peer slot-radio">

                            <div
                                class="px-3 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-xs
                       hover:border-sky-600 hover:text-sky-600
                       peer-checked:bg-sky-600 peer-checked:text-white peer-checked:border-sky-600">
                                {{ $slot }}
                            </div>
                        </label>
                    @empty
                        <p class="text-red-500 text-sm">
                            {{ $isToday ? 'No available slots remaining for today' : 'No slots available' }}</p>
                    @endforelse
                </div>


            </div>

            <!-- Navigation -->
            <div class="flex justify-between mb-6">
                <a href="{{ route('booking', ['step' => 1]) }}"
                    class="px-6 sm:px-8 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-lg text-sm sm:text-base font-medium hover:bg-gray-300">Back</a>
                @if (!empty($slots))
                    <button type="submit" id="nextStepBtn" disabled
                        class="px-6 py-3 bg-sky-600 text-white rounded-lg text-sm font-medium hover:bg-sky-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Next Step
                    </button>
                @endif
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('step2Form');
            const nextBtn = document.getElementById('nextStepBtn');
            const dateInput = document.getElementById('selectedDateInput');
            const slotInput = document.getElementById('selectedSlotInput');
            let selectedDate = dateInput.value;

            // Handle date selection
            document.querySelectorAll('.calendar-day').forEach(day => {
                day.addEventListener('click', function() {
                    if (this.classList.contains('cursor-not-allowed')) return;

                    const date = this.dataset.date;
                    if (date) {
                        // Remove previous selection styling
                        document.querySelectorAll('.calendar-day').forEach(d => {
                            d.classList.remove('bg-sky-600', 'text-white', 'font-semibold',
                                'shadow-md');
                            if (!d.classList.contains('cursor-not-allowed') && !d.classList
                                .contains('bg-sky-100')) {
                                d.classList.add('text-gray-800', 'hover:bg-sky-50');
                            }
                        });

                        // Add selection styling to clicked date
                        this.classList.remove('text-gray-800', 'hover:bg-sky-50', 'bg-sky-100',
                            'text-sky-800', 'border-2', 'border-sky-600');
                        this.classList.add('bg-sky-600', 'text-white', 'font-semibold',
                            'shadow-md');

                        // Update hidden date input
                        dateInput.value = date;
                        selectedDate = date;

                        // Only submit if date changed - to reload slots
                        if (date !== '{{ $selectedDate }}') {
                            // Clear slot selection when date changes
                            slotInput.value = '';

                            // Submit form to reload with new date's slots
                            // form.submit();
                        }
                    }
                });
            });

            // Handle slot selection
            document.querySelectorAll('.slot-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update hidden input
                    slotInput.value = this.value;

                    // Enable next button
                    if (nextBtn) {
                        nextBtn.disabled = false;
                    }

                    // Remove highlight from all slots
                    document.querySelectorAll('.slot-option').forEach(opt => {
                        opt.classList.remove('ring-2', 'ring-sky-500');
                    });

                    // Highlight selected slot
                    this.closest('.slot-option').classList.add('ring-2', 'ring-sky-500');
                });
            });

            // Restore selected slot on page load
            const currentSlot = slotInput.value;
            if (currentSlot) {
                document.querySelectorAll('.slot-radio').forEach(radio => {
                    if (radio.value === currentSlot) {
                        radio.checked = true;
                        radio.closest('.slot-option').classList.add('ring-2', 'ring-sky-500');
                        if (nextBtn) {
                            nextBtn.disabled = false;
                        }
                    }
                });
            }
        });
    </script>
