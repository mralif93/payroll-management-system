<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Annual Leave',
                'code' => 'AL',
                'is_paid' => true,
                'default_days_per_year' => 14,
                'color' => 'indigo',
                'description' => 'Standard paid annual leave entitlement under EA 1955',
            ],
            [
                'name' => 'Medical / Sick Leave',
                'code' => 'MC',
                'is_paid' => true,
                'default_days_per_year' => 14,
                'color' => 'emerald',
                'description' => 'Outpatient certified medical leave with Clinic MC',
            ],
            [
                'name' => 'Hospitalization Leave',
                'code' => 'HL',
                'is_paid' => true,
                'default_days_per_year' => 60,
                'color' => 'teal',
                'description' => 'Inpatient hospital admission leave entitlement',
            ],
            [
                'name' => 'Maternity Leave',
                'code' => 'ML',
                'is_paid' => true,
                'default_days_per_year' => 98,
                'color' => 'purple',
                'description' => 'Malaysian EA 2022 amended 98-day paid maternity leave',
            ],
            [
                'name' => 'Paternity Leave',
                'code' => 'PL',
                'is_paid' => true,
                'default_days_per_year' => 7,
                'color' => 'blue',
                'description' => 'Malaysian EA 2022 amended 7-day paid paternity leave',
            ],
            [
                'name' => 'Unpaid / No-Pay Leave',
                'code' => 'UL',
                'is_paid' => false,
                'default_days_per_year' => 0,
                'color' => 'rose',
                'description' => 'Non-paid absence incurring Ordinary Rate of Pay (ORP) salary deduction',
            ],
        ];

        foreach ($types as $typeData) {
            LeaveType::firstOrCreate(['code' => $typeData['code']], $typeData);
        }

        // Initialize annual balances for all active employees for current year
        $currentYear = (int) date('Y');
        $allTypes = LeaveType::all();
        $employees = Employee::all();

        foreach ($employees as $employee) {
            foreach ($allTypes as $leaveType) {
                // Different entitlement for permanent vs intern/part-time
                $quota = $leaveType->default_days_per_year;
                if ($employee->employment_type === 'intern') {
                    $quota = ($leaveType->code === 'MC' || $leaveType->code === 'AL') ? 6.0 : 0.0;
                } elseif ($employee->employment_type === 'part_time') {
                    $quota = 0.0;
                }

                EmployeeLeaveBalance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                    ],
                    [
                        'total_entitled' => $quota,
                        'taken_days' => 0.0,
                        'pending_days' => 0.0,
                        'remaining_days' => $quota,
                    ]
                );
            }
        }
    }
}
