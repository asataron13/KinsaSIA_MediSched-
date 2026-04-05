<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('first_name',   'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('patient_code','like', "%{$search}%")
            );
        }

        $patients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load(
            'user',
            'appointments.doctor',
            'appointments.department',
            'medicalRecords.doctor'
        );

        return view('admin.patients.show', compact('patient'));
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete(); // cascades to patient via migration
        return redirect()->route('admin.patients.index')->with('success', 'Patient account deleted.');
    }
}