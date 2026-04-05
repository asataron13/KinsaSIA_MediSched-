<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorsController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('department', 'user');

        if ($request->filled('status')) {
            $query->where('availability_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
            );
        }

        $doctors     = $query->paginate(12)->withQueryString();
        $departments = Department::all();

        return view('admin.doctors.index', compact('doctors', 'departments'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('department', 'user', 'appointments.patient', 'appointments.department');
        return view('admin.doctors.show', compact('doctor'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.doctors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email|unique:users,email',
            'phone'               => 'nullable|string|max:20|unique:users,phone',
            'password'            => 'required|string|min:8|confirmed',
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'department_id'       => 'required|exists:departments,id',
            'experience_years'    => 'required|integer|min:0',
            'rating'              => 'nullable|numeric|min:0|max:5',
            'schedule_start'      => 'required|date_format:H:i',
            'schedule_end'        => 'required|date_format:H:i',
            'availability_status' => 'required|in:Available,In Session,Day Off',
        ]);

        if (!$request->email && !$request->phone) {
            return back()->withErrors(['email' => 'Provide at least an email or phone.'])->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'doctor',
        ]);

        Doctor::create([
            'user_id'             => $user->id,
            'department_id'       => $request->department_id,
            'first_name'          => $request->first_name,
            'last_name'           => $request->last_name,
            'experience_years'    => $request->experience_years,
            'rating'              => $request->rating ?? 0.0,
            'schedule_start'      => $request->schedule_start,
            'schedule_end'        => $request->schedule_end,
            'availability_status' => $request->availability_status,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor account created.');
    }

    public function edit(Doctor $doctor)
    {
        $departments = Department::all();
        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'department_id'       => 'required|exists:departments,id',
            'experience_years'    => 'required|integer|min:0',
            'rating'              => 'nullable|numeric|min:0|max:5',
            'schedule_start'      => 'required|date_format:H:i',
            'schedule_end'        => 'required|date_format:H:i',
            'availability_status' => 'required|in:Available,In Session,Day Off',
        ]);

        $doctor->update($request->only([
            'first_name', 'last_name', 'department_id',
            'experience_years', 'rating',
            'schedule_start', 'schedule_end', 'availability_status',
        ]));

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete(); // cascades to doctor
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor account deleted.');
    }
}