<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Pending appointments assigned to this doctor.
     */
    public function queue()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'No doctor profile found.');
        }

        $appointments = $doctor->appointments()
            ->with('patient', 'department')
            ->where('status', 'Pending')
            ->orderBy('appointment_date')
            ->get();

        return view('doctor.queue', compact('appointments', 'doctor'));
    }

    /**
     * Appointments this doctor has accepted (Confirmed / In Progress / Completed).
     * Ordered: In Progress first, then Confirmed, then Completed — SQLite compatible.
     */
    public function accepted()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'No doctor profile found.');
        }

        $appointments = $doctor->appointments()
            ->with('patient', 'department')
            ->whereIn('status', ['Confirmed', 'In Progress', 'Completed'])
            ->orderByRaw("CASE status
                WHEN 'In Progress' THEN 1
                WHEN 'Confirmed'   THEN 2
                WHEN 'Completed'   THEN 3
                ELSE 4 END")
            ->orderBy('appointment_date')
            ->get();

        return view('doctor.accepted', compact('appointments', 'doctor'));
    }

    /**
     * Accept a pending appointment → Confirmed.
     */
    public function accept(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        if ($appointment->status !== 'Pending') {
            return back()->with('error', 'This appointment is no longer pending.');
        }

        $appointment->update(['status' => 'Confirmed']);

        return back()->with('success', 'Appointment accepted.');
    }

    /**
     * Decline a pending or confirmed appointment → Cancelled.
     */
    public function decline(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        if (!in_array($appointment->status, ['Pending', 'Confirmed'])) {
            return back()->with('error', 'This appointment cannot be declined.');
        }

        $appointment->update(['status' => 'Cancelled']);

        return back()->with('success', 'Appointment declined.');
    }

    /**
     * Advance: Confirmed → In Progress → Completed.
     */
    public function advance(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $next = [
            'Confirmed'   => 'In Progress',
            'In Progress' => 'Completed',
        ];

        $newStatus = $next[$appointment->status] ?? null;

        if (!$newStatus) {
            return back()->with('error', 'Cannot advance this appointment.');
        }

        $appointment->update(['status' => $newStatus]);

        return back()->with('success', "Appointment marked as {$newStatus}.");
    }

    private function authorizeDoctor(Appointment $appointment): void
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403);
        }
    }
}