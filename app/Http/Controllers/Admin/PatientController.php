<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PatientService;
use App\Services\Admin\PatientExportImportService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    protected PatientService $patientService;
    protected PatientExportImportService $csvService;

    public function __construct(PatientService $patientService, PatientExportImportService $csvService)
    {
        $this->patientService = $patientService;
        $this->csvService = $csvService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'search' => $request->input('search'),
                'blood_group' => $request->input('blood_group'),
                'status' => $request->input('status'),
            ];

            $patients = $this->patientService->getPatients($filters);

            // Build the HTML for patient rows
            $html = view('admin.partials.patient-cards', compact('patients'))->render();

            // Return JSON with HTML and pagination data
            return response()->json([
                'html' => $html,
                'pagination' => [
                    'current_page' => $patients->currentPage(),
                    'last_page' => $patients->lastPage(),
                    'from' => $patients->firstItem(),
                    'to' => $patients->lastItem(),
                    'total' => $patients->total(),
                ],
            ]);
        }

        $patients = $this->patientService->getPatients();

        return view('admin.patients', compact('patients'));
    }

    public function show(Request $request, $id)
    {
        $patient = $this->patientService->getPatientById($id);

        if ($request->ajax()) {
            $html = view('admin.partials.patient-view', compact('patient'))->render();

            return response()->json(['html' => $html]);
        }

        return view('admin.patient-detail', compact('patient'));
    }

    public function edit(Request $request, $id)
    {
        $patient = $this->patientService->getPatientById($id);

        if ($request->ajax()) {
            $html = view('admin.partials.patient-edit', compact('patient'))->render();

            return response()->json(['html' => $html]);
        }

        return view('admin.partials.patient-edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email|max:255',
                'phone' => ['nullable', 'regex:/^[0-9]{10,15}$/', 'not_regex:/^0+$/'],
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'blood_group' => 'nullable|string|max:5',
                'address' => 'nullable|string|min:10|max:500',
                'emergency_contact' => ['nullable', 'regex:/^[0-9]{10,15}$/', 'not_regex:/^0+$/'],
                'emergency_contact_name' => 'nullable|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/',
                'medical_history' => 'nullable|string|max:2000',
                'current_medications' => 'nullable|string|max:2000',
                'insurance_provider' => 'nullable|string|max:255',
                'insurance_number' => 'nullable|string|max:100',
                'status' => 'required|in:active,inactive',
            ], [
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'first_name.min' => 'First name must be at least 2 characters.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
                'last_name.min' => 'Last name must be at least 2 characters.',
                'phone.regex' => 'Phone number must be between 10-15 digits.',
                'phone.not_regex' => 'Phone number cannot be all zeros.',
                'date_of_birth.before' => 'Date of birth must be before today.',
                'address.min' => 'Address must be at least 10 characters.',
                'emergency_contact.regex' => 'Emergency contact must be between 10-15 digits.',
                'emergency_contact.not_regex' => 'Emergency contact cannot be all zeros.',
                'emergency_contact_name.regex' => 'Emergency contact name can only contain letters and spaces.',
                'emergency_contact_name.min' => 'Emergency contact name must be at least 2 characters.',
            ]);

            $result = $this->patientService->updatePatient($id, $validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => $result,
                    'message' => $result ? 'Patient updated successfully' : 'Failed to update patient',
                ]);
            }

            return redirect()->route('admin.patients')->with(
                $result ? 'success' : 'error',
                $result ? 'Patient updated successfully' : 'Failed to update patient'
            );
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating patient: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the patient: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while updating the patient');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $result = $this->patientService->deletePatient($id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => $result,
                    'message' => $result ? 'Patient deleted successfully' : 'Failed to delete patient',
                ]);
            }

            return redirect()->route('admin.patients')->with(
                $result ? 'success' : 'error',
                $result ? 'Patient deleted successfully' : 'Failed to delete patient'
            );
        } catch (\Exception $e) {
            \Log::error('Error deleting patient: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the patient',
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while deleting the patient');
        }
    }

    /**
     * Export patients to CSV
     */
    public function exportCSV()
    {
        return $this->csvService->exportPatientsToCSV();
    }

    /**
     * Import patients from CSV
     */

    public function importCSV(Request $request)
    {
        try {
            // Validate the CSV file
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:5120',
            ], [
                'csv_file.required' => 'Please select a CSV file to import.',
                'csv_file.mimes' => 'The file must be a CSV file.',
                'csv_file.max' => 'The file size must not exceed 5MB.',
            ]);

            $file = $request->file('csv_file');

            if (!$file || !$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload',
                    'errors' => ['csv_file' => ['The uploaded file is invalid.']],
                ], 422);
            }

            // Get optional column mapping from request
            $columnMapping = null;
            if ($request->has('column_mapping')) {
                $mappingJson = $request->input('column_mapping');
                $columnMapping = json_decode($mappingJson, true);
            }

            // Call your CSV service
            $result = $this->csvService->importPatientsFromCSV($file, $columnMapping);

            $message = "Import completed! Successfully imported {$result['success']} patient(s).";

            if ($result['failed'] > 0) {
                $message .= " {$result['failed']} patient(s) failed to import.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'details' => $result, // details should include success, failed, and errors array
            ]);
        } catch (ValidationException $e) {
            \Log::error('CSV Import Validation Error: ' . json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('CSV Import Error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'errors' => ['csv_file' => [$e->getMessage()]],
            ], 422);
        }
    }

    /**
     * Get CSV headers for field mapping
     */
    public function getCSVHeaders(Request $request)
    {
        try {
            $validated = $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:5120',
            ], [
                'csv_file.required' => 'Please select a CSV file',
                'csv_file.mimes' => 'File must be a CSV file',
                'csv_file.max' => 'File size must not exceed 5MB',
            ]);

            $file = $request->file('csv_file');

            if (!$file || !$file->isValid()) {
                throw new \Exception('File upload failed or file is invalid');
            }

            // Read file and extract headers
            $fileHandle = fopen($file->getRealPath(), 'r');
            if ($fileHandle === false) {
                throw new \Exception('Unable to open CSV file');
            }

            $headers = fgetcsv($fileHandle);
            fclose($fileHandle);

            if ($headers === false || empty($headers)) {
                throw new \Exception('CSV file is empty or invalid');
            }

            // Normalize headers: trim, remove BOM, and filter
            $headers = array_map(function ($h) {
                return trim(preg_replace('/\x{FEFF}/u', '', $h));
            }, $headers);
            $headers = array_filter($headers); // Remove empty headers
            $headers = array_values($headers); // Reindex array

            // Define available form fields for mapping
            $formFields = [
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'date_of_birth' => 'Date of Birth',
                'gender' => 'Gender',
                'address' => 'Address',
                'blood_group' => 'Blood Group',
                'emergency_contact_name' => 'Emergency Contact Name',
                'emergency_contact_phone' => 'Emergency Contact Phone',
                'medical_history' => 'Medical History',
                'current_medications' => 'Current Medications',
                'insurance_provider' => 'Insurance Provider',
                'insurance_number' => 'Insurance Number',
                'status' => 'Status',
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'msg' => 'CSV headers retrieved successfully',
                    'csv_headers' => $headers,
                    'form_fields' => $formFields,
                ], 200);
            }

            return back()->with('headers', $headers)->with('form_fields', $formFields);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('CSV Header Extraction Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
