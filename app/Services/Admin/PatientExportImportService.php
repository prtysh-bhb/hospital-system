<?php

namespace App\Services\Admin;

use App\Models\PatientProfile;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientExportImportService
{
    /**
     * =========================
     * EXPORT PATIENTS TO CSV
     * =========================
     */
    public function exportPatientsToCSV(): StreamedResponse
    {
        $fileName = 'patients_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM (Excel support)
            fwrite($file, "\xEF\xBB\xBF");

            // CSV headers
            fputcsv($file, [
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Date of Birth (DD-MM-YYYY)',
                'Gender',
                'Address',
                'Blood Group',
                'Emergency Contact Name',
                'Emergency Contact Phone',
                'Medical History',
                'Current Medications',
                'Insurance Provider',
                'Insurance Number',
                'Status',
            ]);

            User::where('role', 'patient')
                ->with('patientProfile')
                ->chunk(100, function ($patients) use ($file) {
                    foreach ($patients as $patient) {
                        $profile = $patient->patientProfile;

                        // Excel-safe values
                        $phone = $patient->phone ? "'".$patient->phone : '';
                        $emergencyPhone = $profile?->emergency_contact_phone
                            ? "'".$profile->emergency_contact_phone
                            : '';

                        // DOB as TEXT to avoid Excel auto-format
                        $dob = $patient->date_of_birth
                            ? "'".Carbon::parse($patient->date_of_birth)->format('d-m-Y')
                            : '';

                        fputcsv($file, [
                            $patient->first_name ?? '',
                            $patient->last_name ?? '',
                            $patient->email ?? '',
                            $phone,
                            $dob,
                            $patient->gender ?? '',
                            $patient->address ?? '',
                            $profile?->blood_group ?? '',
                            $profile?->emergency_contact_name ?? '',
                            $emergencyPhone,
                            $profile?->medical_history ?? '',
                            $profile?->current_medications ?? '',
                            $profile?->insurance_provider ?? '',
                            $profile?->insurance_number ?? '',
                            $patient->status ?? 'active',
                        ]);
                    }
                });

            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * =========================
     * IMPORT PATIENTS FROM CSV
     * =========================
     */
    public function importPatientsFromCSV($file, $columnMapping = null): array
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'total' => 0,
        ];

        \Log::info('=== Starting Patient Import ===');
        \Log::info('Column Mapping:', ['mapping' => $columnMapping]);

        $handle = fopen($file->getRealPath(), 'r');

        // Read & clean headers ONLY ONCE
        $headers = fgetcsv($handle);
        if (! $headers) {
            throw new Exception('CSV is empty');
        }

        $headers = array_map(function ($h) {
            return trim(preg_replace('/\x{FEFF}/u', '', $h));
        }, $headers);

        \Log::info('CSV Headers Found:', ['headers' => $headers]);

        $rowNumber = 1; // actual CSV data row (not header)

        while (($row = fgetcsv($handle)) !== false) {

            // Skip completely empty rows
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $rowNumber++;
            $result['total']++;

            try {
                $data = $this->mapCSVRowToData($row, $headers, $columnMapping);

                \Log::info("Row {$rowNumber} mapped data:", $data);

                $data = $this->validateAndFormat($data);
                $this->createPatient($data);
                $result['success']++;
            } catch (Exception $e) {
                $result['failed']++;
                $result['errors'][] = "Row {$rowNumber}: ".$e->getMessage();
                \Log::error("Row {$rowNumber} error: ".$e->getMessage());
            }
        }

        fclose($handle);

        \Log::info('=== Import Complete ===', ['result' => $result]);

        return $result;
    }

    /**
     * =========================
     * MAP CSV ROW TO DATA
     * =========================
     */
    private function mapCSVRowToData(array $row, array $headers, $columnMapping = null): array
    {
        $data = [];

        foreach ($headers as $index => $header) {
            $value = (string) ($row[$index] ?? '');
            $value = trim($value);

            // Strip leading apostrophe (Excel formatting)
            if (str_starts_with($value, "'")) {
                $value = substr($value, 1);
            }

            // Normalize header for consistency
            $normalizedHeader = trim(preg_replace('/\x{FEFF}/u', '', $header));

            $fieldName = null;

            // If custom column mapping is provided, use it
            if ($columnMapping && is_array($columnMapping) && ! empty($columnMapping)) {
                // Try exact match first with normalized headers
                foreach ($columnMapping as $csvHeaderKey => $dbField) {
                    $normalizedKey = trim(preg_replace('/\x{FEFF}/u', '', $csvHeaderKey));

                    if ($normalizedKey === $normalizedHeader ||
                        strtolower($normalizedKey) === strtolower($normalizedHeader)) {
                        $fieldName = $dbField;
                        break;
                    }
                }
            }

            // If not found in custom mapping, try default mapping
            if (! $fieldName) {
                $fieldName = $this->getDefaultFieldMapping($normalizedHeader);
            }

            // Set field value if we found a mapping
            if ($fieldName && ! empty($fieldName)) {
                $this->setFieldValue($data, $fieldName, $value);
            }
        }

        return $data;
    }

    /**
     * =========================
     * MAP ROW (LEGACY)
     * =========================
     */
    private function mapRow(array $headers, array $row): array
    {
        return $this->mapCSVRowToData($row, $headers, null);
    }

    /**
     * =========================
     * GET DEFAULT FIELD MAPPING
     * =========================
     */
    private function getDefaultFieldMapping($header)
    {
        // Normalize header for comparison
        $normalized = strtolower(trim($header));

        // Primary mappings
        $mapping = [
            'first name' => 'first_name',
            'firstname' => 'first_name',
            'first_name' => 'first_name',
            'fname' => 'first_name',

            'last name' => 'last_name',
            'lastname' => 'last_name',
            'last_name' => 'last_name',
            'lname' => 'last_name',
            'surname' => 'last_name',

            'email' => 'email',
            'email address' => 'email',
            'e-mail' => 'email',

            'phone' => 'phone',
            'phone number' => 'phone',
            'contact' => 'phone',
            'contact number' => 'phone',
            'mobile' => 'phone',
            'mobile number' => 'phone',
            'telephone' => 'phone',

            'date of birth' => 'date_of_birth',
            'dob' => 'date_of_birth',
            'birth date' => 'date_of_birth',
            'birthday' => 'date_of_birth',

            'gender' => 'gender',
            'sex' => 'gender',
            'gender identity' => 'gender',

            'address' => 'address',
            'street address' => 'address',
            'home address' => 'address',

            'blood group' => 'blood_group',
            'blood type' => 'blood_group',
            'blood' => 'blood_group',
            'blood_group' => 'blood_group',

            'emergency contact name' => 'emergency_contact_name',
            'emergency contact' => 'emergency_contact_name',
            'emergency contact person' => 'emergency_contact_name',

            'emergency contact phone' => 'emergency_contact_phone',
            'emergency contact number' => 'emergency_contact_phone',

            'medical history' => 'medical_history',
            'past medical history' => 'medical_history',
            'medical_history' => 'medical_history',
            'pmh' => 'medical_history',

            'current medications' => 'current_medications',
            'current drugs' => 'current_medications',
            'medications' => 'current_medications',
            'current_medications' => 'current_medications',

            'insurance provider' => 'insurance_provider',
            'insurance company' => 'insurance_provider',
            'insurance' => 'insurance_provider',

            'insurance number' => 'insurance_number',
            'insurance id' => 'insurance_number',
            'policy number' => 'insurance_number',

            'status' => 'status',
            'active' => 'status',
        ];

        return $mapping[$normalized] ?? null;
    }

    /**
     * =========================
     * SET FIELD VALUE
     * =========================
     */
    private function setFieldValue(&$data, $fieldName, $value)
    {
        switch ($fieldName) {
            case 'email':
                $data['email'] = strtolower($value);
                break;
            case 'phone':
            case 'emergency_contact_phone':
                $data[$fieldName] = $this->digits($value);
                break;
            case 'gender':
                $data['gender'] = strtolower($value);
                break;
            case 'blood_group':
                $data['blood_group'] = strtoupper($value);
                break;
            case 'status':
                $data['status'] = ! empty($value) ? strtolower($value) : 'active';
                break;
            default:
                $data[$fieldName] = $value;
                break;
        }
    }

    /**
     * =========================
     * VALIDATE & FORMAT DATA
     * =========================
     */
    private function validateAndFormat(array $data): array
    {
        if (empty($data['first_name'])) {
            throw new Exception('First name is required');
        }

        if (empty($data['last_name'])) {
            throw new Exception('Last name is required');
        }

        if (! filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email: '.($data['email'] ?? 'empty'));
        }

        // Email must be unique across all users
        if (User::where('email', $data['email'])->exists()) {
            throw new Exception('Email already exists in the system');
        }

        if (empty($data['phone'])) {
            throw new Exception('Phone number is required');
        }

        if (strlen($data['phone']) < 10) {
            throw new Exception('Invalid phone number (must be at least 10 digits)');
        }

        if (User::where('phone', $data['phone'])->exists()) {
            throw new Exception('Phone number already exists');
        }

        if (empty($data['date_of_birth'])) {
            throw new Exception('Date of birth is required');
        }

        $dob = $this->parseDate($data['date_of_birth']);

        if ($dob->isFuture() || $dob->year < 1900) {
            throw new Exception('Invalid date of birth (must be a past date after 1900)');
        }

        $data['date_of_birth'] = $dob->format('Y-m-d');

        return $data;
    }

    /**
     * =========================
     * DOB PARSER (STRICT)
     * =========================
     */
    private function parseDate(string $value): Carbon
    {
        $value = trim($value);

        if (is_numeric($value)) {
            return Carbon::createFromTimestampUTC(((int) $value - 25569) * 86400);
        }

        if (preg_match('/^(\d{2}-\d{2})-(\d{2})$/', $value, $m)) {
            $year = (int) $m[2];
            $century = $year >= 50 ? '19' : '20';
            $value = $m[1].'-'.$century.$year;
        }

        $formats = [
            'd-m-Y',
            'Y-m-d',
            'd/m/Y',
            'd.m.Y',
        ];

        foreach ($formats as $format) {
            $dt = Carbon::createFromFormat($format, $value, null, true);
            if ($dt !== false) {
                return $dt;
            }
        }

        throw new Exception("Invalid date format: {$value}. Use DD-MM-YYYY");
    }

    /**
     * =========================
     * CREATE PATIENT
     * =========================
     */
    private function createPatient(array $data): void
    {
        DB::transaction(function () use ($data) {

            // Double-check before creating
            if (User::where('email', $data['email'])->orWhere('username', $data['email'])->exists()) {
                throw new Exception('This email is already registered in the system');
            }

            if (User::where('phone', $data['phone'])->exists()) {
                throw new Exception('Phone number already exists, record skipped');
            }

            $user = User::create([
                'role' => 'patient',
                // 'username' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'address' => $data['address'] ?? null,
                'status' => $data['status'] ?? 'active',
                'password' => Hash::make(uniqid()),
            ]);

            PatientProfile::create([
                'user_id' => $user->id,
                'blood_group' => $data['blood_group'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'medical_history' => $data['medical_history'] ?? null,
                'current_medications' => $data['current_medications'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'insurance_number' => $data['insurance_number'] ?? null,
            ]);
        });
    }

    private function digits(string $value): string
    {
        return $this->cleanPhoneNumber($value);
    }

    /**
     * =========================
     * CLEAN PHONE NUMBER
     * =========================
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
}
