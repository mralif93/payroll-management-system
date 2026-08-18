<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            // 1. Contractual & Variable Allowances (Subject to EPF, SOCSO, EIS, PCB)
            [
                'code' => 'ATT_ALLOW',
                'name' => 'Attendance Allowance',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'HOUSING_ALLOW',
                'name' => 'Housing / Living Allowance (COLA)',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'SHIFT_ALLOW',
                'name' => 'Shift Allowance',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'COMMISSION',
                'name' => 'Sales Commission',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'OVERTIME',
                'name' => 'Overtime (OT) Payment',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'SERVICE_CHARGE',
                'name' => 'Service Charge / Service Points',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => true,
                'is_eis_subject' => true,
                'is_pcb_subject' => true,
            ],

            // 2. Annual Bonus & Gratuity (Subject to EPF/PCB but exempt from SOCSO/EIS)
            [
                'code' => 'ANNUAL_BONUS',
                'name' => 'Annual Performance Bonus',
                'type' => 'allowance',
                'is_epf_subject' => true,
                'is_socso_subject' => false, // Exempt from SOCSO/EIS under Act 4
                'is_eis_subject' => false,
                'is_pcb_subject' => true,
            ],
            [
                'code' => 'GRATUITY',
                'name' => 'Retirement / Service Gratuity',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false, // Tax-exempt under conditions
            ],

            // 3. Official Reimbursements & Travel Concessions (Statutorily Exempt from EPF, SOCSO, EIS)
            [
                'code' => 'TRAVEL_ALLOW',
                'name' => 'Transport / Travel Allowance',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'PHONE_ALLOW',
                'name' => 'Mobile / Phone Allowance',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'MEAL_ALLOW',
                'name' => 'Meal Allowance',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'PARKING_CLAIM',
                'name' => 'Parking & Toll Reimbursement',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'MEDICAL_CLAIM',
                'name' => 'Medical & Clinic Reimbursement',
                'type' => 'allowance',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],

            // 4. Standard Deductions
            [
                'code' => 'UNPAID_LEAVE',
                'name' => 'Unpaid Leave Deduction',
                'type' => 'deduction',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'SALARY_ADVANCE',
                'name' => 'Salary Advance Repayment',
                'type' => 'deduction',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'STAFF_LOAN',
                'name' => 'Staff Loan Installment',
                'type' => 'deduction',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
            [
                'code' => 'ZAKAT',
                'name' => 'Zakat Pendapatan',
                'type' => 'deduction',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => true, // Tax rebate
            ],
            [
                'code' => 'TABUNG_HAJI',
                'name' => 'Tabung Haji Deduction',
                'type' => 'deduction',
                'is_epf_subject' => false,
                'is_socso_subject' => false,
                'is_eis_subject' => false,
                'is_pcb_subject' => false,
            ],
        ];

        foreach ($components as $c) {
            SalaryComponent::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
