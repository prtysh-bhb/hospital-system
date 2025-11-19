<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Services\admin\DoctoreServices;
use Illuminate\Http\Request;

class docktorsController extends Controller
{
    protected DoctoreServices $doctoreServices;

    public function __construct(DoctoreServices $doctoreServices)
    {
        $this->doctoreServices = $doctoreServices;
    }

    public function index(Request $request)
    {
        $specialties = Specialty::where('status', 'active')->get();

        if ($request->ajax()) {
            $filters = [
                'search' => $request->input('search'),
                'specialty_id' => $request->input('specialty_id'),
                'status' => $request->input('status'),
            ];

            $doctors = $this->doctoreServices->getDoctors($filters);

            return view('admin.partials.doctor-cards', compact('doctors'))->render();
        }

        $doctors = $this->doctoreServices->getDoctors();

        return view('admin.doctors', compact('doctors', 'specialties'));
    }

    public function create(Request $request)
    {
        $specialties = Specialty::where('status', 'active')->get();
        return view('admin.doctor-add', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'specialty_id' => 'required|exists:specialties,id',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:50',
            'consultation_fee' => 'required|numeric|min:0',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'languages' => 'nullable|string',
            'schedules' => 'nullable|array',
            'schedules.*.enabled' => 'boolean',
            'schedules.*.start_time' => 'required_if:schedules.*.enabled,true|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.enabled,true|date_format:H:i|after:schedules.*.start_time',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/doctors'), $imageName);
            $validated['profile_image'] = 'uploads/doctors/' . $imageName;
        }

        try {
            $doctor = $this->doctoreServices->createDoctor($validated);
            
            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor added successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to add doctor. Please try again.');
        }
    }

    public function edit($id)
    {
        $doctor = $this->doctoreServices->getDoctorById($id);
        
        if (!$doctor) {
            return redirect()->route('admin.doctors')
                ->with('error', 'Doctor not found.');
        }
        
        $specialties = Specialty::where('status', 'active')->get();
        return view('admin.doctor-add', compact('doctor', 'specialties'));
    }

    public function update(Request $request, $id)
    {
        // Build validation rules dynamically
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'specialty_id' => 'required|exists:specialties,id',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'license_number' => 'required|string|max:50',
            'consultation_fee' => 'required|numeric|min:0',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'languages' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'available_for_booking' => 'required|boolean',
            'schedules' => 'nullable|array',
            'schedules.*.enabled' => 'boolean',
            'schedules.*.start_time' => 'required_if:schedules.*.enabled,true|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.enabled,true|date_format:H:i|after:schedules.*.start_time',
        ];

        $validated = $request->validate($rules);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/doctors'), $imageName);
            $validated['profile_image'] = 'uploads/doctors/' . $imageName;
        }

        try {
            $doctor = $this->doctoreServices->updateDoctor($id, $validated);
            
            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update doctor. Please try again.');
        }
    }

    public function destroy($id)
    {
        $User = $this->doctoreServices->deleteDoctor($id);

        if ($User) {
            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor deleted successfully!');
        } else {
            return redirect()->route('admin.doctors')
                ->with('error', 'Failed to delete doctor. Please try again.');
        }
    }
}
