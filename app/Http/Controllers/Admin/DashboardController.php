<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients     = Patient::count();
        $totalDoctors      = Doctor::count();
        $pendingBookings   = Appointment::where('status', 'Pending')->count();
        $confirmedCount    = Appointment::where('status', 'Confirmed')->count();
        $inProgressCount   = Appointment::where('status', 'In Progress')->count();
        $completedCount    = Appointment::where('status', 'Completed')->count();
        $cancelledCount    = Appointment::where('status', 'Cancelled')->count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();

        $recentAppointments = Appointment::with('patient', 'doctor', 'department')
            ->latest()->take(8)->get();

        $pendingAppointments = Appointment::with('patient', 'doctor', 'department')
            ->where('status', 'Pending')
            ->orderBy('appointment_date')
            ->take(6)->get();

        return view('admin.dashboard', compact(
            'totalPatients', 'totalDoctors',
            'pendingBookings', 'confirmedCount', 'inProgressCount',
            'completedCount', 'cancelledCount', 'todayAppointments',
            'recentAppointments', 'pendingAppointments'
        ));
    }
}