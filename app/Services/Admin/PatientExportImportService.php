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
        $fileName = 'patients_' . now()->format('Y-m-d_H-i-s') . '.csv';

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
                        $phone = $patient->phone ? "'" . $patient->phone : '';
                        $emergencyPhone = $profile?->emergency_contact_phone
                            ? "'" . $profile->emergency_contact_phone
                            : '';

                        // DOB as TEXT to avoid Excel auto-format
                        $dob = $patient->date_of_birth
                            ? "'" . Carbon::parse($patient->date_of_birth)->format('d-m-Y')
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
    public function importPatientsFromCSV($file): array
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'total' => 0,
        ];

        $handle = fopen($file->getRealPath(), 'r');

        // Read & clean headers ONLY ONCE
        $headers = fgetcsv($handle);
        if (!$headers) {
            throw new Exception('CSV is empty');
        }

        $headers = array_map(function ($h) {
            return strtolower(trim(preg_replace('/\x{FEFF}/u', '', $h)));
        }, $headers);

        $rowNumber = 1; // actual CSV data row (not header)

        while (($row = fgetcsv($handle)) !== false) {

            // Skip completely empty rows
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $rowNumber++;
            $result['total']++;

            try {
                $data = $this->mapRow($headers, $row);
                $data = $this->validateAndFormat($data);
                $this->createPatient($data);
                $result['success']++;
            } catch (Exception $e) {
                $result['failed']++;
                $result['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);
        return $result;
    }

    /**
     * =========================
     * MAP CSV ROW
     * =========================
     */
    private function mapRow(array $headers, array $row): array
    {
        $data = [];

        foreach ($headers as $i => $header) {

            // Normalize header
            $header = trim($header);
            $header = preg_replace('/\x{FEFF}/u', '', $header); // remove BOM
            $header = strtolower($header);

            // Normalize value
            $value = trim($row[$i] ?? '');
            if (str_starts_with($value, "'")) {
                $value = substr($value, 1);
            }

            switch ($header) {
                case 'first name':
                    $data['first_name'] = $value;
                    break;

                case 'last name':
                    $data['last_name'] = $value;
                    break;

                case 'email':
                    $data['email'] = strtolower($value);
                    break;

                case 'phone':
                    $data['phone'] = $this->digits($value);
                    break;

                case 'date of birth':
                case 'date of birth (dd-mm-yyyy)':
                    $data['date_of_birth'] = $value;
                    break;

                case 'gender':
                    $data['gender'] = strtolower($value);
                    break;

                case 'address':
                    $data['address'] = $value;
                    break;

                case 'blood group':
                    $data['blood_group'] = strtoupper($value);
                    break;

                case 'emergency contact name':
                    $data['emergency_contact_name'] = $value;
                    break;

                case 'emergency contact phone':
                    $data['emergency_contact_phone'] = $this->digits($value);
                    break;

                case 'medical history':
                    $data['medical_history'] = $value;
                    break;

                case 'current medications':
                    $data['current_medications'] = $value;
                    break;

                case 'insurance provider':
                    $data['insurance_provider'] = $value;
                    break;

                case 'insurance number':
                    $data['insurance_number'] = $value;
                    break;

                case 'status':
                    $data['status'] = $value ?: 'active';
                    break;
            }
        }

        return $data;
    }


    /**
     * =========================
     * VALIDATE & FORMAT DATA
     * =========================
     */
    private function validateAndFormat(array $data): array
    {
        if (empty($data['first_name']) || empty($data['last_name'])) {
            throw new Exception('First & Last name required');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }

        //  Email already used by another role
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser && $existingUser->role !== 'patient') {
            throw new Exception('Email already used by another user');
        }

        if (strlen($data['phone']) < 10) {
            throw new Exception('Invalid phone number');
        }

        if (User::where('phone', $data['phone'])->exists()) {
            throw new Exception('Phone number already exists');
        }

        if (empty($data['date_of_birth'])) {
            throw new Exception('Date of birth required');
        }

        $dob = $this->parseDate($data['date_of_birth']);

        if ($dob->isFuture() || $dob->year < 1900) {
            throw new Exception('Invalid date of birth');
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

        // Excel numeric date
        if (is_numeric($value)) {
            return Carbon::createFromTimestampUTC(((int) $value - 25569) * 86400);
        }

        // Reject ONLY pure 2-digit year
        if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $value)) {
            throw new Exception('Use 4-digit year (DD-MM-YYYY)');
        }

        foreach (['d-m-Y', 'Y-m-d', 'd/m/Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (Exception $e) {
            }
        }

        throw new Exception('Invalid DOB format');
    }

    /**
     * =========================
     * CREATE PATIENT
     * =========================
     */

    private function createPatient(array $data): void
    {
        DB::transaction(function () use ($data) {

            if (User::where('email', $data['email'])->exists()) {
                throw new Exception('Email already exists');
            }


            if (User::where('phone', $data['phone'])->exists()) {
                throw new Exception('Phone number already exists, record skipped');
            }

            $user = User::create([
                'role' => 'patient',
                'username' => $data['email'],
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
        return preg_replace('/\D/', '', $value);
    }
}
