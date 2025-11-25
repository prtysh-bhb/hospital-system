<?php

namespace App\Http\Controllers\admin;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\SpecialtysServices;

class SpecialtysController extends Controller
{
    protected $specialtysServices;
    public function __construct(SpecialtysServices $specialtys)
    {
        $this->specialtysServices = $specialtys;
    }

    public function index()
    {
        return view('admin.specialtys.index');
    }

    public function getModel(Request $request)
    {
        $data = [];

        if (isset($request->id) && $request->id != '') {
            $data = Specialty::where('id', $request->id)->first();
        }

        return view('admin.specialtys.getmodel', compact('data'));
    }

    public function getList(Request $request)
    {
        return response()->json(
            $this->specialtysServices->getList($request)
        );
    }
    public function store(Request $request)
    {
        $result = $this->specialtysServices->store($request);
        return response()->json($result);
    }
    public function toggleStatus(Request $request)
    {
        $result = $this->specialtysServices->toggleStatus($request);
        return response()->json($result);
    }
    public function destroy($id)
    {
        return $this->specialtysServices->destroy($id);
    }
}
