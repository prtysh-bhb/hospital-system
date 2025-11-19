@if ($patients->isEmpty())
<tr>
    <td colspan="8" class="px-6 py-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No patients found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search.</p>
    </td>
</tr>
@else
    @php
        $avatarColors = [
            'bg-sky-100 text-sky-600',
            'bg-purple-100 text-purple-600',
            'bg-emerald-100 text-emerald-600',
            'bg-pink-100 text-pink-600',
            'bg-amber-100 text-amber-600',
            'bg-indigo-100 text-indigo-600',
        ];
    @endphp

    @foreach ($patients as $index => $patient)
        @php
            $color = $avatarColors[$index % count($avatarColors)];
            $initials = strtoupper(substr($patient->user->first_name, 0, 1) . substr($patient->user->last_name, 0, 1));
            $age = $patient->user->date_of_birth ? \Carbon\Carbon::parse($patient->user->date_of_birth)->age : 'N/A';
            $lastVisit = $patient->appointments()->latest()->first();
        @endphp

        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-medium text-sky-600">
                    #PT{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </td>

            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 {{ $color }} rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $initials }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">{{ $patient->user->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->user->email }}</p>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 hidden lg:table-cell">
                <p class="text-sm text-gray-800">{{ $age }} / {{ ucfirst($patient->user->gender ?? 'N/A') }}</p>
            </td>

            <td class="px-6 py-4 hidden md:table-cell">
                @if ($patient->blood_group)
                    <span class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                        {{ $patient->blood_group }}
                    </span>
                @else
                    <span class="text-sm text-gray-500">N/A</span>
                @endif
            </td>

            <td class="px-6 py-4 hidden lg:table-cell">
                <p class="text-sm text-gray-800">{{ $patient->user->phone }}</p>
            </td>

            <td class="px-6 py-4 hidden md:table-cell">
                @if ($lastVisit)
                    <p class="text-sm text-gray-800">{{ $lastVisit->appointment_date->diffForHumans() }}</p>
                    <p class="text-xs text-gray-500">{{ $lastVisit->appointment_date->format('d M Y') }}</p>
                @else
                    <p class="text-sm text-gray-500">No visits</p>
                @endif
            </td>

            <td class="px-6 py-4">
                <span class="px-3 py-1 text-xs font-medium rounded-full
                    @if ($patient->user->status === 'active') bg-green-100 text-green-700
                    @elseif ($patient->user->status === 'inactive') bg-gray-100 text-gray-700
                    @else bg-amber-100 text-amber-700 @endif">
                    {{ ucfirst($patient->user->status) }}
                </span>
            </td>

             <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <button class="text-sky-600 hover:text-sky-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    <button class="text-amber-600 hover:text-amber-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
@endif
