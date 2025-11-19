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
}
