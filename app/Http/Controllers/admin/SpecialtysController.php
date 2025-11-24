<?php

namespace App\Http\Controllers\admin;

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
    public function getList(Request $request)
    {
        return response()->json(
            $this->specialtysServices->getList($request)
        );
    }
    public function destroy($id)
    {
        return $this->specialtysServices->destroy($id);
    }
}
