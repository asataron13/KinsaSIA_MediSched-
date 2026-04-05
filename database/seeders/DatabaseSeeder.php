<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Departments ───────────────────────────────────────────────────────
        $depts = [
            ['name' => 'General Medicine',        'description' => 'Primary care and general health consultations.'],
            ['name' => 'Cardiology',               'description' => 'Heart and cardiovascular system specialists.'],
            ['name' => 'Pediatrics',               'description' => 'Healthcare for infants, children, and adolescents.'],
            ['name' => 'Orthopedics',              'description' => 'Bone, joint, and musculoskeletal conditions.'],
            ['name' => 'Dermatology',              'description' => 'Skin, hair, and nail disorders.'],
            ['name' => 'Neurology',                'description' => 'Brain and nervous system disorders.'],
            ['name' => 'Obstetrics & Gynecology',  'description' => "Women's reproductive health and maternity care."],
            ['name' => 'Ophthalmology',            'description' => 'Eye health and vision care.'],
        ];

        foreach ($depts as $d) {
            Department::firstOrCreate(['name' => $d['name']], $d);
        }

        $ids = Department::pluck('id', 'name');

        // ── Admin ─────────────────────────────────────────────────────────────
        User::firstOrCreate(['email' => 'admin@medisched.com'], [
            'name'     => 'System Admin',
            'phone'    => '09000000001',
            'password' => Hash::make('Admin@1234'),
            'role'     => 'admin',
        ]);

        // ── Doctors ───────────────────────────────────────────────────────────
        $doctors = [
            [
                'user'    => ['name' => 'Maria Santos',   'email' => 'dr.santos@medisched.com',   'phone' => '09100000001'],
                'profile' => ['first_name' => 'Maria',  'last_name' => 'Santos',   'department_id' => $ids['General Medicine'],       'experience_years' => 10, 'rating' => 4.8, 'schedule_start' => '08:00:00', 'schedule_end' => '17:00:00', 'availability_status' => 'Available'],
            ],
            [
                'user'    => ['name' => 'Jose Reyes',     'email' => 'dr.reyes@medisched.com',    'phone' => '09100000002'],
                'profile' => ['first_name' => 'Jose',   'last_name' => 'Reyes',    'department_id' => $ids['Cardiology'],              'experience_years' => 15, 'rating' => 4.9, 'schedule_start' => '09:00:00', 'schedule_end' => '18:00:00', 'availability_status' => 'Available'],
            ],
            [
                'user'    => ['name' => 'Ana Dela Cruz',  'email' => 'dr.delacruz@medisched.com', 'phone' => '09100000003'],
                'profile' => ['first_name' => 'Ana',    'last_name' => 'Dela Cruz','department_id' => $ids['Pediatrics'],              'experience_years' =>  8, 'rating' => 4.7, 'schedule_start' => '08:00:00', 'schedule_end' => '16:00:00', 'availability_status' => 'Available'],
            ],
            [
                'user'    => ['name' => 'Carlo Mendoza',  'email' => 'dr.mendoza@medisched.com',  'phone' => '09100000004'],
                'profile' => ['first_name' => 'Carlo',  'last_name' => 'Mendoza',  'department_id' => $ids['Orthopedics'],             'experience_years' => 12, 'rating' => 4.6, 'schedule_start' => '10:00:00', 'schedule_end' => '19:00:00', 'availability_status' => 'In Session'],
            ],
            [
                'user'    => ['name' => 'Rosa Villanueva','email' => 'dr.villa@medisched.com',    'phone' => '09100000005'],
                'profile' => ['first_name' => 'Rosa',   'last_name' => 'Villanueva','department_id' => $ids['Dermatology'],            'experience_years' =>  6, 'rating' => 4.5, 'schedule_start' => '08:00:00', 'schedule_end' => '15:00:00', 'availability_status' => 'Day Off'],
            ],
        ];

        foreach ($doctors as $data) {
            $user = User::firstOrCreate(['email' => $data['user']['email']], array_merge($data['user'], [
                'password' => Hash::make('Doctor@1234'),
                'role'     => 'doctor',
            ]));

            if (!$user->doctor) {
                Doctor::create(array_merge($data['profile'], ['user_id' => $user->id]));
            }
        }

        // ── Patient ───────────────────────────────────────────────────────────
        $patientUser = User::firstOrCreate(['email' => 'patient@medisched.com'], [
            'name'     => 'Juan dela Cruz',
            'phone'    => '09200000001',
            'password' => Hash::make('Patient@1234'),
            'role'     => 'patient',
        ]);

        if (!$patientUser->patient) {
            Patient::create([
                'user_id'      => $patientUser->id,
                'patient_code' => 'PT-00001',
                'first_name'   => 'Juan',
                'last_name'    => 'dela Cruz',
                'birth_date'   => '1995-06-15',
                'gender'       => 'Male',
                'address'      => '123 Rizal Street, Barangay Uno, Manila',
                'weight_kg'    => 70.50,
                'height_cm'    => 170.00,
                'status'       => 'Active',
            ]);
        }

        // ── Settings ──────────────────────────────────────────────────────────
        Setting::updateOrCreate(['id' => 1], [
            'hospital_name'              => 'MediSched General Hospital',
            'system_name'                => 'MediSched',
            'contact_number'             => '(02) 8123-4567',
            'email'                      => 'info@medisched.com',
            'address'                    => '1 Hospital Drive, Manila, Philippines',
            'email_notifications'        => true,
            'sms_notifications'          => true,
            'new_booking_alerts'         => false,
            'cancellation_alerts'        => true,
            'max_bookings_per_day'       => 30,
            'booking_window_days'        => 30,
            'consultation_duration_mins' => 30,
            'cancellation_policy_hours'  => 24,
        ]);

        $this->command->info('✅ Seeding complete!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@medisched.com',        'Admin@1234'],
                ['Doctor',  'dr.santos@medisched.com',    'Doctor@1234'],
                ['Doctor',  'dr.reyes@medisched.com',     'Doctor@1234'],
                ['Doctor',  'dr.delacruz@medisched.com',  'Doctor@1234'],
                ['Doctor',  'dr.mendoza@medisched.com',   'Doctor@1234'],
                ['Doctor',  'dr.villa@medisched.com',     'Doctor@1234'],
                ['Patient', 'patient@medisched.com',      'Patient@1234'],
            ]
        );
    }
}