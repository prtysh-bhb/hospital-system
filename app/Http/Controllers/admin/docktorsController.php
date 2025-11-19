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
        
        if ($request->ajax()) {
            return view('admin.doctor-add', compact('specialties'));
        }
        
        return view('admin.doctor-add', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[0-9]{10,15}$/', 'unique:users,phone', 'not_regex:/^0+$/'],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|min:10|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'specialty_id' => 'required|exists:specialties,id',
            'qualification' => 'required|string|min:2|max:255',
            'experience_years' => 'required|integer|min:0|max:70',
            'license_number' => 'required|string|min:3|max:50',
            'consultation_fee' => 'required|numeric|min:0|max:100000',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'languages' => 'nullable|string|max:255',
            'schedules' => 'nullable|array',
            'schedules.*.enabled' => 'boolean',
            'schedules.*.start_time' => 'required_if:schedules.*.enabled,true|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.enabled,true|date_format:H:i|after:schedules.*.start_time',
        ], [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'phone.regex' => 'Phone number must be between 10-15 digits.',
            'phone.not_regex' => 'Phone number cannot be all zeros.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'address.min' => 'Address must be at least 10 characters.',
            'qualification.min' => 'Qualification must be at least 2 characters.',
            'license_number.min' => 'License number must be at least 3 characters.',
            'experience_years.max' => 'Experience years cannot exceed 70.',
            'consultation_fee.max' => 'Consultation fee cannot exceed ₹100,000.',
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
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor added successfully!',
                    'doctor' => $doctor
                ]);
            }
            
            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor added successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add doctor. Please try again.',
                    'error' => $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Failed to add doctor. Please try again.');
        }
    }

    public function edit(Request $request, $id)
    {
        $doctor = $this->doctoreServices->getDoctorById($id);
        
        if (!$doctor) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Doctor not found.'], 404);
            }
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
            'first_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => ['required', 'regex:/^[0-9]{10,15}$/', 'unique:users,phone,' . $id, 'not_regex:/^0+$/'],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|min:10|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'specialty_id' => 'required|exists:specialties,id',
            'qualification' => 'required|string|min:2|max:255',
            'experience_years' => 'required|integer|min:0|max:70',
            'license_number' => 'required|string|min:3|max:50',
            'consultation_fee' => 'required|numeric|min:0|max:100000',
            'slot_duration' => 'required|integer|in:15,30,45,60',
            'languages' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'available_for_booking' => 'required|boolean',
            'schedules' => 'nullable|array',
            'schedules.*.enabled' => 'boolean',
            'schedules.*.start_time' => 'required_if:schedules.*.enabled,true|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.enabled,true|date_format:H:i|after:schedules.*.start_time',
        ];

        $messages = [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'phone.regex' => 'Phone number must be between 10-15 digits.',
            'phone.not_regex' => 'Phone number cannot be all zeros.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'address.min' => 'Address must be at least 10 characters.',
            'qualification.min' => 'Qualification must be at least 2 characters.',
            'license_number.min' => 'License number must be at least 3 characters.',
            'experience_years.max' => 'Experience years cannot exceed 70.',
            'consultation_fee.max' => 'Consultation fee cannot exceed ₹100,000.',
        ];

        $validated = $request->validate($rules, $messages);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/doctors'), $imageName);
            $validated['profile_image'] = 'uploads/doctors/' . $imageName;
        }

        try {
            $doctor = $this->doctoreServices->updateDoctor($id, $validated);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doctor updated successfully!',
                    'doctor' => $doctor
                ]);
            }
            
            return redirect()->route('admin.doctors')
                ->with('success', 'Doctor updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update doctor. Please try again.',
                    'error' => $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Failed to update doctor. Please try again.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $User = $this->doctoreServices->deleteDoctor($id);

            if ($User) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Doctor deleted successfully!'
                    ]);
                }
                
                return redirect()->route('admin.doctors')
                    ->with('success', 'Doctor deleted successfully!');
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to delete doctor. Please try again.'
                    ], 422);
                }
                
                return redirect()->route('admin.doctors')
                    ->with('error', 'Failed to delete doctor. Please try again.');
            }
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the doctor.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.doctors')
                ->with('error', 'An error occurred while deleting the doctor.');
        }
    }
}
