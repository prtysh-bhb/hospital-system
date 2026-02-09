<?php

namespace App\Services\Admin;

use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorCSVService
{
    /**
     * Export all doctors to CSV file
     *
     * @return StreamedResponse
     */
    public function exportDoctorsToCSV()
    {
        try {
            $fileName = 'doctors_' . now()->format('Y-m-d_H-i-s') . '.csv';

            return response()->streamDownload(function () {
                $file = fopen('php://output', 'w');

                // Write CSV headers
                fputcsv($file, [
                    'First Name',
                    'Last Name',
                    'Username',
                    'Email',
                    'Phone',
                    'Date of Birth',
                    'Gender',
                    'Address',
                    'Specialty',
                    'Qualification',
                    'Experience Years',
                    'License Number',
                    'Consultation Fee',
                    'Status',
                    'Available for Booking',
                    'Working Days',
                    'Bio',
                ]);

                // Query doctors with chunking to avoid memory issues
                DoctorProfile::with('user', 'specialty', 'schedules')
                    ->whereHas('user')
                    ->chunk(100, function ($doctors) use ($file) {
                        foreach ($doctors as $doctor) {
                            if ($doctor->user) {
                                $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                                // Get working days string
                                $schedules = DoctorSchedule::where('doctor_id', $doctor->user_id)
                                    ->where('is_available', true)
                                    ->orderBy('day_of_week')
                                    ->get();

                                $workingDays = [];
                                foreach ($schedules as $schedule) {
                                    try {
                                        $dayName = $dayNames[$schedule->day_of_week];
                                        // Handle both datetime and time formats
                                        $startTime = is_string($schedule->start_time)
                                            ? substr($schedule->start_time, 0, 5)
                                            : $schedule->start_time->format('H:i');
                                        $endTime = is_string($schedule->end_time)
                                            ? substr($schedule->end_time, 0, 5)
                                            : $schedule->end_time->format('H:i');
                                        $workingDays[] = "{$dayName} {$startTime}-{$endTime}";
                                    } catch (Exception $e) {
                                        // Skip on error
                                        continue;
                                    }
                                }
                                $workingDaysStr = implode(', ', $workingDays);

                                fputcsv($file, [
                                    $doctor->user->first_name ?? '',
                                    $doctor->user->last_name ?? '',
                                    $doctor->user->username ?? '',
                                    $doctor->user->email ?? '',
                                    $doctor->user->phone ?? '',
                                    $doctor->user->date_of_birth ? $doctor->user->date_of_birth->format('Y-m-d') : '',
                                    $doctor->user->gender ?? '',
                                    $doctor->user->address ?? '',
                                    $doctor->specialty?->name ?? '',
                                    $doctor->qualification ?? '',
                                    $doctor->experience_years ?? 0,
                                    $doctor->license_number ?? '',
                                    $doctor->consultation_fee ?? 0,
                                    $doctor->user->status ?? 'active',
                                    $doctor->available_for_booking ? 'Yes' : 'No',
                                    $workingDaysStr,
                                    $doctor->bio ?? '',
                                ]);
                            }
                        }
                    });

                fclose($file);
            }, $fileName, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
        } catch (Exception $e) {
            \Log::error('CSV Export Error: ' . $e->getMessage());
            throw new Exception('Failed to export doctors: ' . $e->getMessage());
        }
    }

    /**
     * Import doctors from CSV file
     *
     * @param $file
     * @return array ['success' => count, 'failed' => count, 'errors' => []]
     */
    public function importDoctorsFromCSV($file)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        try {
            // Check if file exists and is readable
            if (!file_exists($file->getRealPath())) {
                throw new Exception('File does not exist or is not readable.');
            }

            $fileHandle = fopen($file->getRealPath(), 'r');

            if ($fileHandle === false) {
                throw new Exception('Unable to open CSV file.');
            }

            $headers = fgetcsv($fileHandle);
            if ($headers === false) {
                fclose($fileHandle);
                throw new Exception('CSV file is empty or invalid.');
            }

            $headerCount = count($headers);

            $rowNumber = 2; // Start from 2 as first row is headers

            while (($row = fgetcsv($fileHandle)) !== false) {
                try {
                    // Skip empty rows
                    if (
                        count(array_filter($row, function ($value) {
                            return $value !== null && $value !== '';
                        })) === 0
                    ) {
                        $rowNumber++;
                        continue;
                    }

                    // Validate row has all columns
                    if (count($row) < $headerCount) {
                        $row = array_pad($row, $headerCount, '');
                    }

                    // Map CSV columns to variables
                    $data = $this->mapCSVRowToData($row, $headers);

                    // Validate required fields
                    $validation = $this->validateDoctorData($data);
                    if (!empty($validation['errors'])) {
                        throw new Exception(implode(', ', $validation['errors']));
                    }
                    
                    // Use updated data from validation (includes parsed dates)
                    $data = $validation['data'] ?? $data;

                    // Create or update doctor
                    $this->createOrUpdateDoctor($data);

                    $results['success']++;
                } catch (Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                }

                $rowNumber++;
            }

            fclose($fileHandle);
        } catch (Exception $e) {
            $results['errors'][] = 'File Error: ' . $e->getMessage();
        }

        return $results;
    }

    /**
     * Map CSV row to data array
     *
     * @param array $row
     * @param array $headers
     * @return array
     */
    private function mapCSVRowToData($row, $headers)
    {
        $data = [];

        foreach ($headers as $index => $header) {
            $value = (string) ($row[$index] ?? '');

            // Trim the value
            $value = trim($value);

            // Normalize header names and map to data
            $header = trim($header);

            switch ($header) {
                case 'First Name':
                    $data['first_name'] = $value;
                    break;
                case 'Last Name':
                    $data['last_name'] = $value;
                    break;
                case 'Username':
                    $data['username'] = $value;
                    break;
                case 'Email':
                    $data['email'] = strtolower($value);
                    break;
                case 'Phone':
                    $data['phone'] = $this->cleanPhoneNumber($value);
                    break;
                case 'Date of Birth':
                    $data['date_of_birth'] = $value;
                    break;
                case 'Gender':
                    $data['gender'] = strtolower($value);
                    break;
                case 'Address':
                    $data['address'] = $value;
                    break;
                case 'Specialty':
                    $data['specialty_name'] = $value;
                    break;
                case 'Qualification':
                    $data['qualification'] = $value;
                    break;
                case 'Experience Years':
                    $data['experience_years'] = is_numeric($value) ? (int) $value : 0;
                    break;
                case 'License Number':
                    $data['license_number'] = $value;
                    break;
                case 'Consultation Fee':
                    $data['consultation_fee'] = is_numeric($value) ? (float) $value : 0.0;
                    break;
                case 'Status':
                    $data['status'] = !empty($value) ? strtolower($value) : 'active';
                    break;
                case 'Available for Booking':
                    $data['available_for_booking'] = strtolower($value) === 'yes';
                    break;
                case 'Working Days':
                    $data['working_days'] = $value;
                    break;
                case 'Bio':
                    $data['bio'] = $value;
                    break;
            }
        }

        return $data;
    }

    /**
     * Validate doctor data
     *
     * @param array $data
     * @return array ['errors' => []]
     */
    private function validateDoctorData($data)
    {
        $errors = [];

        // Validate required fields - with clear field names in error messages
        if (empty($data['first_name'])) {
            $errors[] = '[First Name] First name is required';
        }
        if (empty($data['last_name'])) {
            $errors[] = '[Last Name] Last name is required';
        }
        if (empty($data['email'])) {
            $errors[] = '[Email] Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '[Email] Invalid email format (received: ' . $data['email'] . ')';
        }
        if (empty($data['phone'])) {
            $errors[] = '[Phone] Phone is required';
        } else {
            $phoneDigits = preg_replace('/\D/', '', $data['phone']);
            $phoneLength = strlen($phoneDigits);

            if ($phoneLength < 10 || $phoneLength > 15) {
                $errors[] = "[Phone] Phone must be 10-15 digits (received: {$phoneLength} digits from '{$data['phone']}')";
            }

            // Also validate the cleaned phone has only digits
            if (!preg_match('/^[0-9]+$/', $phoneDigits)) {
                $errors[] = '[Phone] Phone must contain only digits';
            }

            // Store the cleaned phone for further processing
            $data['phone'] = $phoneDigits;
        }
        if (empty($data['specialty_name'])) {
            $errors[] = '[Specialty] Specialty is required';
        }
        if (empty($data['qualification'])) {
            $errors[] = '[Qualification] Qualification is required';
        }
        if (!isset($data['experience_years']) || $data['experience_years'] < 0) {
            $errors[] = '[Experience Years] Experience years must be a positive number';
        }
        if (empty($data['license_number'])) {
            $errors[] = '[License Number] License number is required';
        }
        if (!isset($data['consultation_fee']) || $data['consultation_fee'] < 0) {
            $errors[] = '[Consultation Fee] Consultation fee must be a positive number';
        }
        if (empty($data['address'])) {
            $errors[] = '[Address] Address is required';
        }
        if (empty($data['gender']) || !in_array($data['gender'], ['male', 'female', 'other'])) {
            $errors[] = '[Gender] Gender must be male, female, or other (received: ' . ($data['gender'] ?? 'empty') . ')';
        }
        if (empty($data['date_of_birth'])) {
            $errors[] = '[Date of Birth] Date of birth is required';
        } else {
            try {
                // Try to parse date in multiple formats
                $dob = $this->parseDate($data['date_of_birth']);
                if ($dob->isFuture()) {
                    $errors[] = '[Date of Birth] Date of birth must be in the past';
                }
                // Update data with standardized format
                $data['date_of_birth'] = $dob->format('Y-m-d');
            } catch (Exception $e) {
                $errors[] = '[Date of Birth] Invalid date format. Use DD-MM-YYYY, MM-DD-YYYY, or YYYY-MM-DD (received: ' . $data['date_of_birth'] . ')';
            }
        }

        return ['errors' => $errors, 'data' => $data];
    }

    /**
     * Create or update doctor
     *
     * @param array $data
     * @throws Exception
     */
    private function createOrUpdateDoctor($data)
    {
        DB::beginTransaction();

        try {

            // Check if a user exists with the given email or phone
            $existingUser = User::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();

            if ($existingUser) {
                if ($existingUser->email === $data['email'] && $existingUser->id != ($user->id ?? 0)) {
                    throw new Exception('This email is already taken.');
                }
                if ($existingUser->phone === $data['phone'] && $existingUser->id != ($user->id ?? 0)) {
                    throw new Exception('This phone number is already taken.');
                }
            }

            // Check if user already exists by email
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                // Update existing user
                $user->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'date_of_birth' => Carbon::parse($data['date_of_birth'])->format('Y-m-d'),
                    'gender' => $data['gender'],
                    'address' => $data['address'],
                    'status' => $data['status'] ?? 'active',
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'role' => 'doctor',
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'username' => $data['email'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['phone']),
                    'date_of_birth' => Carbon::parse($data['date_of_birth'])->format('Y-m-d'),
                    'gender' => $data['gender'],
                    'address' => $data['address'],
                    'status' => $data['status'] ?? 'active',
                ]);
            }

            // Get or create specialty
            $specialty = Specialty::where('name', $data['specialty_name'])->first();
            if (!$specialty) {
                $specialty = Specialty::create([
                    'name' => $data['specialty_name'],
                    'description' => "Auto-created from CSV import",
                    'status' => 'active',
                ]);
            }

            // Create or update doctor profile
            $doctorProfile = DoctorProfile::where('user_id', $user->id)->first();

            if ($doctorProfile) {
                $doctorProfile->update([
                    'specialty_id' => $specialty->id,
                    'qualification' => $data['qualification'],
                    'experience_years' => $data['experience_years'],
                    'consultation_fee' => $data['consultation_fee'],
                    'license_number' => $data['license_number'],
                    'available_for_booking' => $data['available_for_booking'] ?? true,
                    'bio' => $data['bio'] ?? null,
                ]);
            } else {
                $doctorProfile = DoctorProfile::create([
                    'user_id' => $user->id,
                    'specialty_id' => $specialty->id,
                    'qualification' => $data['qualification'],
                    'experience_years' => $data['experience_years'],
                    'consultation_fee' => $data['consultation_fee'],
                    'license_number' => $data['license_number'],
                    'available_for_booking' => $data['available_for_booking'] ?? true,
                    'bio' => $data['bio'] ?? null,
                ]);
            }

            // Handle working days schedule if provided
            if (!empty($data['working_days'])) {
                $this->createScheduleFromWorkingDays($user->id, $data['working_days']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create/update doctor: ' . $e->getMessage());
        }
    }

    /**
     * Create doctor schedule from working days string
     * Format: "Monday 09:00-17:00, Wednesday 10:00-18:00"
     * Uses updateOrCreate to handle existing schedules without constraint violations
     *
     * @param int $userId
     * @param string $workingDaysString
     */
    private function createScheduleFromWorkingDays($userId, $workingDaysString)
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $dayMap = array_flip($dayNames);

        // Parse working days string
        $schedules = explode(',', $workingDaysString);

        foreach ($schedules as $schedule) {
            $schedule = trim($schedule);
            if (empty($schedule)) {
                continue;
            }

            // Parse format: "Monday 09:00-17:00" or "Monday 9:00-17:00"
            preg_match('/^(\w+)\s+(\d{1,2}):(\d{2})-(\d{1,2}):(\d{2})$/', $schedule, $matches);

            if (!$matches) {
                continue;
            }

            $dayName = $matches[1];
            $startHour = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $startMin = $matches[3];
            $endHour = str_pad($matches[4], 2, '0', STR_PAD_LEFT);
            $endMin = $matches[5];

            if (!isset($dayMap[$dayName])) {
                continue;
            }

            $dayOfWeek = $dayMap[$dayName];

            // Use updateOrCreate to avoid duplicate key constraint violations
            // This will update existing schedule or create new one
            DoctorSchedule::updateOrCreate(
                [
                    'doctor_id' => $userId,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'start_time' => "{$startHour}:{$startMin}:00",
                    'end_time' => "{$endHour}:{$endMin}:00",
                    'slot_duration' => 30,
                    'max_patients' => 0,
                    'is_available' => true,
                    'notes' => null,
                ]
            );
        }
    }

    /**
     * Get working days string from doctor schedules
     *
     * @param DoctorProfile $doctor
     * @return string
     */
    private function getWorkingDaysString($doctor)
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedules = DoctorSchedule::where('doctor_id', $doctor->user_id)
            ->where('is_available', true)
            ->orderBy('day_of_week')
            ->get();

        $workingDays = [];
        foreach ($schedules as $schedule) {
            try {
                $dayName = $dayNames[$schedule->day_of_week];
                // Handle both datetime and time formats
                $startTime = is_string($schedule->start_time)
                    ? substr($schedule->start_time, 0, 5)
                    : $schedule->start_time->format('H:i');
                $endTime = is_string($schedule->end_time)
                    ? substr($schedule->end_time, 0, 5)
                    : $schedule->end_time->format('H:i');
                $workingDays[] = "{$dayName} {$startTime}-{$endTime}";
            } catch (Exception $e) {
                continue;
            }
        }

        return implode(', ', $workingDays);
    }

    /**
     * Clean and normalize phone number
     * Removes spaces, dashes, parentheses, and other common formatting
     * Keeps only digits
     *
     * @param string $phone
     * @return string
     */
    private function cleanPhoneNumber($phone)
    {
        $phone = trim($phone);

        // If scientific notation (e.g. 7.90E+09)
        if (stripos($phone, 'e') !== false) {
            $phone = number_format((float) $phone, 0, '', '');
        }

        // Remove non-digits
        return preg_replace('/[^0-9]/', '', $phone);
    }


    /**
     * Generate username from first and last name
     *
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    private function generateUsername($firstName, $lastName)
    {
        $firstName = strtolower(preg_replace('/[^a-zA-Z]/', '', $firstName));
        $lastName = strtolower(preg_replace('/[^a-zA-Z]/', '', $lastName));

        if (empty($lastName)) {
            return $firstName;
        }

        return $firstName . '.' . $lastName;
    }

    /**
     * Parse date in multiple formats
     * Supports: DD-MM-YYYY, DD-MM-YY, MM-DD-YYYY, MM-DD-YY, YYYY-MM-DD, DD/MM/YYYY, MM/DD/YYYY, etc.
     *
     * @param string $dateString
     * @return Carbon
     * @throws Exception
     */
    private function parseDate($dateString)
    {
        $dateString = trim($dateString);

        // Try different date formats
        $formats = [
            'Y-m-d',      // YYYY-MM-DD
            'YYYY-MM-DD',
            'd-m-Y',      // DD-MM-YYYY
            'd-m-y',      // DD-MM-YY
            'm-d-Y',      // MM-DD-YYYY
            'm-d-y',      // MM-DD-YY
            'd/m/Y',      // DD/MM/YYYY
            'd/m/y',      // DD/MM/YY
            'm/d/Y',      // MM/DD/YYYY
            'm/d/y',      // MM/DD/YY
            'd.m.Y',      // DD.MM.YYYY
            'd.m.y',      // DD.MM.YY
            'Y/m/d',      // YYYY/MM/DD
            'Y-m-d H:i:s', // With time
        ];

        foreach ($formats as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $dateString);
                if ($parsed) {
                    return $parsed;
                }
            } catch (Exception $e) {
                continue;
            }
        }

        // If all else fails, try Carbon's flexible parsing
        try {
            return Carbon::parse($dateString);
        } catch (Exception $e) {
            throw new Exception("Could not parse date: {$dateString}");
        }
    }
}