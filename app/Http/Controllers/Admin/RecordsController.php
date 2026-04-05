<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with('patient', 'doctor', 'appointment');

        if ($request->filled('type')) {
            $query->where('record_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) =>
                $q->where('first_name',    'like', "%{$search}%")
                  ->orWhere('last_name',   'like', "%{$search}%")
                  ->orWhere('patient_code','like', "%{$search}%")
            );
        }

        $records = $query->latest()->paginate(15)->withQueryString();

        return view('admin.records.index', compact('records'));
    }

    public function create()
    {
        $patients     = Patient::orderBy('last_name')->get();
        $doctors      = Doctor::with('department')->orderBy('last_name')->get();
        $appointments = Appointment::with('patient')
            ->whereIn('status', ['Confirmed', 'In Progress', 'Completed'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('admin.records.create', compact('patients', 'doctors', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'appointment_id' => 'required|exists:appointments,id',
            'record_type'    => 'required|in:Medical History,Lab Results,Prescription,Billing',
            'diagnosis'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
            'status'         => 'required|in:Filed,Reviewed,Pending,Paid',
            'record_date'    => 'required|date',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('admin.records.index')->with('success', 'Record created.');
    }

    public function destroy(MedicalRecord $record)
    {
        $record->delete();
        return redirect()->route('admin.records.index')->with('success', 'Record deleted.');
    }
}