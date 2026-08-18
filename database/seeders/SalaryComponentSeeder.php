<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            // Allowances
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
            // Deductions
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
            SalaryComponent::firstOrCreate(['code' => $c['code']], $c);
        }
    }
}
