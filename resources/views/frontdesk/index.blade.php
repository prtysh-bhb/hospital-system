@extends('layouts.frontdesk')

@section('title', 'Appointments')
@section('page-title', 'All Appointments')

@section('content')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">All Appointments</h2>
                <p class="text-sm text-gray-500">Manage appointment status</p>
            </div>

            <a href="{{ route('frontdesk.add-appointment') }}"
                class="px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700">
                + Add Appointment
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class="hover:bg-gray-50" data-id="{{ $appointment->id }}"
                            data-status="{{ $appointment->status }}">

                            <td class="px-4 py-3 border font-medium">
                                {{ $appointment->patient->full_name ?? $appointment->first_name . ' ' . $appointment->last_name }}
                            </td>

                            <td class="px-4 py-3 border">
                                {{ $appointment->doctor->full_name ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-3 border">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3 border">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>

                            <td class="px-4 py-3 border capitalize">
                                {{ str_replace('_', ' ', $appointment->appointment_type) }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3 border">
                                <span
                                    class="status-badge inline-block px-2 py-1 text-xs rounded
                                @if ($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($appointment->status == 'confirmed') bg-blue-100 text-blue-700
                                @elseif($appointment->status == 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-4 py-3 border">
                                @if (in_array($appointment->status, ['completed', 'cancelled']))
                                    <span class="text-xs text-gray-400 italic">Closed</span>
                                @else
                                    <select class="status-select border rounded px-2 py-1 text-xs">
                                        <option value="">Change</option>

                                        @if ($appointment->status === 'pending')
                                            <option value="confirmed">Confirm</option>
                                            <option value="cancelled">Cancel</option>
                                        @endif

                                        @if ($appointment->status === 'confirmed')
                                            <option value="completed">Complete</option>
                                            <option value="cancelled">Cancel</option>
                                        @endif
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $appointments->links() }}
        </div>
    </div>

@endsection

@push('scripts')
   <script>
document.querySelectorAll('.status-select').forEach(select => {

    select.addEventListener('change', function () {

        const row = this.closest('tr');
        const id = row.dataset.id;
        const currentStatus = row.dataset.status;
        const newStatus = this.value;

        if (!newStatus || currentStatus === newStatus) return;

        // ✅ HARD STOP double submit
        this.disabled = true;

        fetch(`/frontdesk/appointments/${id}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(async res => {
            const data = await res.json();

            if (!res.ok) {
                throw data;
            }
            return data;
        })
        .then(data => {

            row.dataset.status = data.status;

            const badge = row.querySelector('.status-badge');
            badge.textContent = data.label;
            badge.className = 'status-badge inline-block px-2 py-1 text-xs rounded ' + ({
                pending: 'bg-yellow-100 text-yellow-700',
                confirmed: 'bg-blue-100 text-blue-700',
                completed: 'bg-green-100 text-green-700',
                cancelled: 'bg-red-100 text-red-700'
            })[data.status];

            // Lock UI
            if (['completed', 'cancelled'].includes(data.status)) {
                row.querySelector('td:last-child').innerHTML =
                    '<span class="text-xs text-gray-400 italic">Closed</span>';
            }

        })
        .catch(err => {
            showErrorPopup(err.error || 'Something went wrong');
            select.disabled = false; // re-enable on error
        });
    });

});

// Error popup (unchanged, but moved outside)
function showErrorPopup(message) {
    let modal = document.getElementById('errorModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'errorModal';
        modal.className =
            'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40';
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6 text-center">
                <h2 class="text-lg font-semibold text-red-600 mb-2">Error</h2>
                <p class="text-gray-700 mb-6">${message}</p>
                <button onclick="this.closest('#errorModal').remove()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Close
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    } else {
        modal.querySelector('p').textContent = message;
        modal.style.display = 'flex';
    }
}
</script>

@endpush
