<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveApplication;
use App\Models\EmployeeLeaveBalance;
use App\Models\User;
use Carbon\Carbon;

class LeaveApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $employees = Employee::with('department')->get();
        $leaveTypes = LeaveType::all()->keyBy('code');

        if ($employees->isEmpty() || $leaveTypes->isEmpty()) {
            return;
        }

        $now = Carbon::now();
        $currentYear = $now->year;

        $sampleApplications = [
            [
                'emp_idx' => 0, // Muhammad Alif bin Abdullah
                'type' => 'AL',
                'start_date' => $now->copy()->subDays(20)->format('Y-m-d'),
                'end_date' => $now->copy()->subDays(18)->format('Y-m-d'),
                'total_days' => 3.0,
                'status' => 'approved',
                'reason' => 'Annual family vacation to Pulau Redang',
                'approved_by' => $admin?->id,
                'approved_at' => $now->copy()->subDays(22),
            ],
            [
                'emp_idx' => 1, // Nurul Ain binti Mohd Faizal
                'type' => 'MC',
                'start_date' => $now->copy()->subDays(10)->format('Y-m-d'),
                'end_date' => $now->copy()->subDays(9)->format('Y-m-d'),
                'total_days' => 2.0,
                'status' => 'approved',
                'reason' => 'Severe migraine and fever, Klinik Mediviron MC attached',
                'approved_by' => $admin?->id,
                'approved_at' => $now->copy()->subDays(9),
            ],
            [
                'emp_idx' => 2, // Alexander William Smith
                'type' => 'AL',
                'start_date' => $now->copy()->addDays(5)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(6)->format('Y-m-d'),
                'total_days' => 2.0,
                'status' => 'pending',
                'reason' => 'Attending overseas cybersecurity summit & conference',
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'emp_idx' => 3, // Tan Wei Meng
                'type' => 'AL',
                'start_date' => $now->copy()->addDays(12)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(12)->format('Y-m-d'),
                'total_days' => 1.0,
                'status' => 'pending',
                'reason' => 'Personal matters / renew passport at UTC',
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'emp_idx' => 4, // Siti Nurhaliza binti Kamaruddin
                'type' => 'MC',
                'start_date' => $now->copy()->subDays(4)->format('Y-m-d'),
                'end_date' => $now->copy()->subDays(4)->format('Y-m-d'),
                'total_days' => 1.0,
                'status' => 'approved',
                'reason' => 'Dental appointment and tooth extraction MC',
                'approved_by' => $admin?->id,
                'approved_at' => $now->copy()->subDays(3),
            ],
            [
                'emp_idx' => 0, // Muhammad Alif bin Abdullah
                'type' => 'AL',
                'start_date' => $now->copy()->addDays(25)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(27)->format('Y-m-d'),
                'total_days' => 3.0,
                'status' => 'pending',
                'reason' => 'School mid-term holidays with children',
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'emp_idx' => 1, // Nurul Ain binti Mohd Faizal
                'type' => 'AL',
                'start_date' => $now->copy()->subDays(40)->format('Y-m-d'),
                'end_date' => $now->copy()->subDays(39)->format('Y-m-d'),
                'total_days' => 2.0,
                'status' => 'approved',
                'reason' => 'Hari Raya Aidilfitri extended leave',
                'approved_by' => $admin?->id,
                'approved_at' => $now->copy()->subDays(42),
            ],
            [
                'emp_idx' => min(5, $employees->count() - 1),
                'type' => 'UL',
                'start_date' => $now->copy()->subDays(15)->format('Y-m-d'),
                'end_date' => $now->copy()->subDays(15)->format('Y-m-d'),
                'total_days' => 1.0,
                'status' => 'rejected',
                'reason' => 'Urgent personal trip (exceeded entitlement quota)',
                'approved_by' => $admin?->id,
                'approved_at' => $now->copy()->subDays(14),
            ],
        ];

        foreach ($sampleApplications as $app) {
            $employee = $employees->get($app['emp_idx']) ?? $employees->first();
            $leaveType = $leaveTypes->get($app['type']) ?? $leaveTypes->first();

            LeaveApplication::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'start_date' => $app['start_date'],
                'end_date' => $app['end_date'],
                'total_days' => $app['total_days'],
                'status' => $app['status'],
                'reason' => $app['reason'],
                'approved_by' => $app['approved_by'],
                'approved_at' => $app['approved_at'],
            ]);

            // If approved, update employee leave balance
            if ($app['status'] === 'approved') {
                $balance = EmployeeLeaveBalance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                    ],
                    [
                        'total_entitled' => $leaveType->default_days_per_year,
                        'taken_days' => 0.0,
                        'pending_days' => 0.0,
                        'remaining_days' => $leaveType->default_days_per_year,
                    ]
                );

                $balance->taken_days = (float) $balance->taken_days + $app['total_days'];
                $balance->remaining_days = max(0, (float) $balance->total_entitled - $balance->taken_days);
                $balance->save();
            } elseif ($app['status'] === 'pending') {
                $balance = EmployeeLeaveBalance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                    ],
                    [
                        'total_entitled' => $leaveType->default_days_per_year,
                        'taken_days' => 0.0,
                        'pending_days' => 0.0,
                        'remaining_days' => $leaveType->default_days_per_year,
                    ]
                );

                $balance->pending_days = (float) $balance->pending_days + $app['total_days'];
                $balance->save();
            }
        }
    }
}
