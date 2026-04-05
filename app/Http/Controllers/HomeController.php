<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;

class HomeController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $doctors     = Doctor::with('department')->get();

        return view('home', compact('departments', 'doctors'));
    }
}