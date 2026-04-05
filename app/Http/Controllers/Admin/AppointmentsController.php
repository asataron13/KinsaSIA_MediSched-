<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('patient', 'doctor', 'department');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('patient_code','like', "%{$search}%")
            );
        }

        $appointments = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'         => Appointment::count(),
            'pending'     => Appointment::where('status', 'Pending')->count(),
            'confirmed'   => Appointment::where('status', 'Confirmed')->count(),
            'inprogress'  => Appointment::where('status', 'In Progress')->count(),
            'completed'   => Appointment::where('status', 'Completed')->count(),
            'cancelled'   => Appointment::where('status', 'Cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'counts'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,In Progress,Completed,Cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted.');
    }
}