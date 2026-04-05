<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function book()
    {
        $departments = Department::all();
        $doctors     = Doctor::with('department')
                        ->where('availability_status', '!=', 'Day Off')
                        ->orderBy('last_name')
                        ->get();

        $patient = Auth::check() ? Auth::user()->patient : null;

        return view('appointments.book', compact('departments', 'doctors', 'patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'birth_date'       => 'required|date',
            'gender'           => 'required|in:Male,Female,Other',
            'address'          => 'required|string',
            'weight_kg'        => 'nullable|numeric|min:1|max:500',
            'height_cm'        => 'nullable|numeric|min:1|max:300',
            'department_id'    => 'required|exists:departments,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'type'             => 'required|in:Online,Walk-in',
            'symptoms'         => 'required|string',
        ]);

        $user    = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            $patient = Patient::create([
                'user_id'      => $user->id,
                'patient_code' => 'PT-' . str_pad(Patient::count() + 1, 5, '0', STR_PAD_LEFT),
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'birth_date'   => $request->birth_date,
                'gender'       => $request->gender,
                'address'      => $request->address,
                'weight_kg'    => $request->weight_kg,
                'height_cm'    => $request->height_cm,
                'status'       => 'Pending',
            ]);
        } else {
            $patient->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'birth_date' => $request->birth_date,
                'gender'     => $request->gender,
                'address'    => $request->address,
                'weight_kg'  => $request->weight_kg ?? $patient->weight_kg,
                'height_cm'  => $request->height_cm ?? $patient->height_cm,
            ]);
        }

        $appointment = Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $request->doctor_id,
            'department_id'    => $request->department_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => null,
            'type'             => $request->type,
            'symptoms'         => $request->symptoms,
            'status'           => 'Pending',
        ]);

        return redirect()->route('appointments.confirmation', $appointment);
    }

    public function confirmation(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }

        $appointment->load('patient', 'doctor.department', 'department');

        return view('appointments.confirmation', compact('appointment'));
    }

    public function my()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('appointments.book');
        }

        $appointments = $patient->appointments()
            ->with('doctor', 'department')
            ->latest()
            ->get();

        return view('appointments.my', compact('appointments'));
    }

    /**
     * Cancel is blocked ONLY when BOTH of these are true at the same time:
     *   1. The appointment has been accepted by the doctor (status = Confirmed)
     *   2. The appointment date is within 3 days (3 days or fewer remaining)
     *
     * Either condition alone does NOT block cancellation.
     * Completed and Cancelled appointments are always non-cancellable for obvious reasons.
     */
    public function cancel(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }

        // Already done — nothing to cancel
        if (in_array($appointment->status, ['Completed', 'Cancelled'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $isAccepted   = $appointment->status === 'Confirmed';
        $daysUntil    = Carbon::today()->diffInDays(Carbon::parse($appointment->appointment_date), false);
        $isWithin3Days = $daysUntil <= 3;

        // Block only when BOTH conditions are true simultaneously
        if ($isAccepted && $isWithin3Days) {
            return back()->with('error', 'This appointment can no longer be cancelled. It has been accepted by your doctor and is within 3 days of your scheduled date.');
        }

        $appointment->update(['status' => 'Cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Shared helper — returns true if the patient is allowed to cancel.
     * Used by the view to show/hide the cancel button correctly.
     */
    public static function canCancel(Appointment $appointment): bool
    {
        if (in_array($appointment->status, ['Completed', 'Cancelled'])) {
            return false;
        }

        $isAccepted    = $appointment->status === 'Confirmed';
        $daysUntil     = Carbon::today()->diffInDays(Carbon::parse($appointment->appointment_date), false);
        $isWithin3Days = $daysUntil <= 3;

        // Blocked only when BOTH are true
        return !($isAccepted && $isWithin3Days);
    }
}