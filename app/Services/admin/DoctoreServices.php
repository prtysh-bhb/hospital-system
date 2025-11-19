<?php

namespace App\Services\admin;

use App\Models\DoctorProfile;
use App\Models\User;
use App\Models\DoctorSchedule;

class DoctoreServices
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getDoctors($filters = [])
    {
        $query = DoctorProfile::with('specialty', 'user')
            ->whereHas('user'); // Only include doctors with non-deleted users

        // Search by name, email, or phone
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('specialty', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by specialty
        if (!empty($filters['specialty_id'])) {
            $query->where('specialty_id', $filters['specialty_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        return $query->get();
    }

    public function createDoctor($data)
    {
        \DB::beginTransaction();
        
        try {
            // Create user with phone as password
            $user = User::create([
                'role' => 'doctor',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => \Hash::make($data['phone']), // Phone as password
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'profile_image' => $data['profile_image'] ?? null,
                'status' => 'active',
            ]);

            // Create doctor profile
            $doctorProfile = DoctorProfile::create([
                'user_id' => $user->id,
                'specialty_id' => $data['specialty_id'],
                'qualification' => $data['qualification'],
                'experience_years' => $data['experience_years'],
                'license_number' => $data['license_number'],
                'consultation_fee' => $data['consultation_fee'],
                'bio' => $data['languages'] ?? null,
                'available_for_booking' => true,
            ]);

            // Create schedules if provided
            if (!empty($data['schedules'])) {
                foreach ($data['schedules'] as $dayOfWeek => $schedule) {
                    if (!empty($schedule['enabled'])) {
                        DoctorSchedule::create([
                            'doctor_id' => $user->id,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'slot_duration' => $data['slot_duration'],
                            'max_patients' => 20,
                            'is_available' => true,
                        ]);
                    }
                }
            }

            \DB::commit();
            return $doctorProfile;
            
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function getDoctorById($id)
    {
        return DoctorProfile::with('specialty', 'user', 'user.doctorSchedules')
            ->where('user_id', $id)
            ->first();
    }

    public function updateDoctor($id, $data)
    {
        \DB::beginTransaction();
        
        try {
            // Update user
            $user = User::findOrFail($id);
            $updateData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'status' => $data['status'],
            ];
            
            if (isset($data['profile_image'])) {
                $updateData['profile_image'] = $data['profile_image'];
            }
            
            $user->update($updateData);

            // Update doctor profile
            $doctorProfile = DoctorProfile::where('user_id', $id)->firstOrFail();
            $doctorProfile->update([
                'specialty_id' => $data['specialty_id'],
                'qualification' => $data['qualification'],
                'experience_years' => $data['experience_years'],
                'license_number' => $data['license_number'],
                'consultation_fee' => $data['consultation_fee'],
                'bio' => $data['languages'] ?? null,
                'available_for_booking' => $data['available_for_booking'],
            ]);

            // Delete existing schedules and recreate
            DoctorSchedule::where('doctor_id', $id)->delete();
            
            if (!empty($data['schedules'])) {
                foreach ($data['schedules'] as $dayOfWeek => $schedule) {
                    if (!empty($schedule['enabled'])) {
                        DoctorSchedule::create([
                            'doctor_id' => $id,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'slot_duration' => $data['slot_duration'],
                            'max_patients' => 20,
                            'is_available' => true,
                        ]);
                    }
                }
            }

            \DB::commit();
            return $doctorProfile;
            
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function deleteDoctor($id)
    {
        $user = User::find($id);
        if ($user && $user->role === 'doctor') {
            return $user->delete();
        }
        return false;
    }
}
