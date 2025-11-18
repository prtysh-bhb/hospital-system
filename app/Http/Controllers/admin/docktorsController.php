<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\DoctoreServices;

use Illuminate\Http\Request;


class docktorsController extends Controller
{
    protected DoctoreServices $doctoreServices;
    public function __construct(DoctoreServices $doctoreServices)
    {
        $this->doctoreServices = $doctoreServices;
    }
    public function index(){
        $doctors = $this->doctoreServices->getDoctors(); // Fetch doctors from the database or service

        return view('admin.doctors', compact('doctors'));
    }



}
