<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorsListController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();

        $query = Doctor::with('department');

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $doctors = $query->orderBy('last_name')->get();

        return view('doctors.index', compact('doctors', 'departments'));
    }
}