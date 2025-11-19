<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\admin\PetientService;
use Illuminate\Http\Request;

class PetientController extends Controller
{
    protected PetientService $petientService;

    public function __construct(PetientService $petientService)
    {
        $this->petientService = $petientService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'search' => $request->input('search'),
                'blood_group' => $request->input('blood_group'),
                'status' => $request->input('status'),
            ];

            $patients = $this->petientService->getPatients($filters);

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

        $patients = $this->petientService->getPatients();

        return view('admin.patients', compact('patients'));
    }
}
