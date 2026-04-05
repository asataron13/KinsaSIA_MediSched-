<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorsListController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentsController;
use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\DoctorsController;
use App\Http\Controllers\Admin\RecordsController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// ── Public pages ──────────────────────────────────────────────────────────────
Route::get('/',        [HomeController::class,        'index'])->name('home');
Route::get('/doctors', [DoctorsListController::class, 'index'])->name('doctors.index');
Route::get('/book',    [AppointmentController::class, 'book'])->name('appointments.book');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Patient routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::post('/book',                                       [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/confirmation/{appointment}',     [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
    Route::get('/my-appointments',                             [AppointmentController::class, 'my'])->name('appointments.my');
    Route::patch('/appointments/{appointment}/cancel',         [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// ── Doctor routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {
        Route::get('/queue',                                   [DoctorController::class, 'queue'])->name('queue');
        Route::get('/accepted',                                [DoctorController::class, 'accepted'])->name('accepted');
        Route::patch('/appointments/{appointment}/accept',     [DoctorController::class, 'accept'])->name('appointments.accept');
        Route::patch('/appointments/{appointment}/decline',    [DoctorController::class, 'decline'])->name('appointments.decline');
        Route::patch('/appointments/{appointment}/advance',    [DoctorController::class, 'advance'])->name('appointments.advance');
    });

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Appointments
        Route::get('/appointments',                          [AppointmentsController::class, 'index'])->name('appointments.index');
        Route::patch('/appointments/{appointment}/status',   [AppointmentsController::class, 'updateStatus'])->name('appointments.status');
        Route::delete('/appointments/{appointment}',         [AppointmentsController::class, 'destroy'])->name('appointments.destroy');

        // Patients
        Route::get('/patients',              [PatientsController::class, 'index'])->name('patients.index');
        Route::get('/patients/{patient}',    [PatientsController::class, 'show'])->name('patients.show');
        Route::delete('/patients/{patient}', [PatientsController::class, 'destroy'])->name('patients.destroy');

        // Doctors
        Route::get('/doctors',                   [DoctorsController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create',            [DoctorsController::class, 'create'])->name('doctors.create');
        Route::post('/doctors',                  [DoctorsController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{doctor}',          [DoctorsController::class, 'show'])->name('doctors.show');
        Route::get('/doctors/{doctor}/edit',     [DoctorsController::class, 'edit'])->name('doctors.edit');
        Route::patch('/doctors/{doctor}',        [DoctorsController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}',       [DoctorsController::class, 'destroy'])->name('doctors.destroy');

        // Records
        Route::get('/records',           [RecordsController::class, 'index'])->name('records.index');
        Route::get('/records/create',    [RecordsController::class, 'create'])->name('records.create');
        Route::post('/records',          [RecordsController::class, 'store'])->name('records.store');
        Route::delete('/records/{record}', [RecordsController::class, 'destroy'])->name('records.destroy');

        // Settings
        Route::get('/settings',   [SettingsController::class, 'index'])->name('settings');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });