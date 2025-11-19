@php
    $colors = [
        [
            'gradient' => 'from-sky-500 to-sky-600',
            'avatar' => '0ea5e9',
            'text' => 'text-sky-600',
            'border' => 'border-sky-600',
            'bg' => 'bg-sky-600',
            'hover-bg' => 'hover:bg-sky-700',
            'hover-light' => 'hover:bg-sky-50',
        ],
        [
            'gradient' => 'from-purple-500 to-purple-600',
            'avatar' => '8b5cf6',
            'text' => 'text-purple-600',
            'border' => 'border-purple-600',
            'bg' => 'bg-purple-600',
            'hover-bg' => 'hover:bg-purple-700',
            'hover-light' => 'hover:bg-purple-50',
        ],
        [
            'gradient' => 'from-emerald-500 to-emerald-600',
            'avatar' => '10b981',
            'text' => 'text-emerald-600',
            'border' => 'border-emerald-600',
            'bg' => 'bg-emerald-600',
            'hover-bg' => 'hover:bg-emerald-700',
            'hover-light' => 'hover:bg-emerald-50',
        ],
        [
            'gradient' => 'from-amber-500 to-amber-600',
            'avatar' => 'f59e0b',
            'text' => 'text-amber-600',
            'border' => 'border-amber-600',
            'bg' => 'bg-amber-600',
            'hover-bg' => 'hover:bg-amber-700',
            'hover-light' => 'hover:bg-amber-50',
        ],
        [
            'gradient' => 'from-indigo-500 to-indigo-600',
            'avatar' => '6366f1',
            'text' => 'text-indigo-600',
            'border' => 'border-indigo-600',
            'bg' => 'bg-indigo-600',
            'hover-bg' => 'hover:bg-indigo-700',
            'hover-light' => 'hover:bg-indigo-50',
        ],
    ];
@endphp

@foreach ($doctors as $index => $doctor)
    @php
        $color = $colors[$index % count($colors)];
    @endphp
    @if ($doctor->user)
        <div
            class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-24 sm:h-32 bg-gradient-to-r {{ $color['gradient'] }}"></div>
            <div class="p-4 sm:p-6 -mt-12 sm:-mt-16">
                <div class="flex justify-center mb-4">
                    @if ($doctor->user->profile_image)
                        <img src="{{ asset($doctor->user->profile_image) }}"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg object-cover"
                            alt="{{ $doctor->user->full_name }}"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->full_name) }}&background={{ $color['avatar'] }}&color=fff&size=128"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg hidden"
                            alt="{{ $doctor->user->full_name }}" style="display:none;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->full_name) }}&background={{ $color['avatar'] }}&color=fff&size=128"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg"
                            alt="{{ $doctor->user->full_name }}">
                    @endif
                </div>
                <div class="text-center">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">{{ $doctor->user->full_name }}</h3>
                    <p class="text-xs sm:text-sm {{ $color['text'] }} font-medium mb-2">
                        {{ $doctor->specialty->name ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 mb-1">{{ $doctor->qualification ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 mb-4">{{ $doctor->experience_years }} years experience</p>
                </div>

                <div class="space-y-2 sm:space-y-3 mb-4">
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $doctor->user->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $doctor->user->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>₹{{ number_format($doctor->consultation_fee, 0) }} consultation</span>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    @if ($doctor->user->status === 'active')
                        <span
                            class="px-2 sm:px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                    @elseif($doctor->user->status === 'inactive')
                        <span
                            class="px-2 sm:px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">Inactive</span>
                    @else
                        <span
                            class="px-2 sm:px-3 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">{{ ucfirst($doctor->user->status) }}</span>
                    @endif
                    @if ($doctor->available_for_booking)
                        <span class="text-xs sm:text-sm text-green-600 font-medium">Available</span>
                    @else
                        <span class="text-xs sm:text-sm text-gray-600">Not Available</span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <button
                        class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm {{ $color['text'] }} border {{ $color['border'] }} rounded-lg {{ $color['hover-light'] }} font-medium">View
                        Details</button>
                    <a href="{{ route('admin.doctor-edit', $doctor->user->id) }}"
                        class="flex-1 px-3 sm:px-4 py-2 text-xs sm:text-sm text-white {{ $color['bg'] }} rounded-lg {{ $color['hover-bg'] }} font-medium text-center">Edit</a>
                </div>
                <button data-doctor-id="{{ $doctor->user->id }}"
                    class="delete-doctor-btn w-full px-3 sm:px-4 py-2 text-xs sm:text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 font-medium mt-2">
                    Delete Doctor
                </button>
            </div>
        </div>
    @endif
@endforeach

@if ($doctors->isEmpty())
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No doctors found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
    </div>
@endif
