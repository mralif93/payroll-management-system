<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeStatutoryProfile;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed realistic Malaysian employees covering all contract classifications and statutory profiles.
     */
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $techDept = Department::where('company_id', $company->id)->where('code', 'TECH')->first();
        $finDept = Department::where('company_id', $company->id)->where('code', 'FIN')->first();
        $hraDept = Department::where('company_id', $company->id)->where('code', 'HRA')->first();
        $sbdDept = Department::where('company_id', $company->id)->where('code', 'SBD')->first();
        $execDept = Department::where('company_id', $company->id)->where('code', 'EXEC')->first();

        $employeesData = [
            // 1. Permanent Senior Local Employee (Standard Statutory: 11% EPF / 12% ER, Cat 1 SOCSO, SKBBK, EIS, PCB)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-001',
                    'full_name' => 'Muhammad Alif bin Abdullah',
                    'nric_passport' => '930815-14-5521',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1993-08-15',
                    'joined_date' => '2022-01-10',
                    'basic_salary' => 6500.00,
                    'bank_name' => 'Malayan Banking Berhad (Maybank)',
                    'bank_account_no' => '164128990123',
                    'designation' => 'Lead Software Architect',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'alif.abdullah@payflow.my',
                    'phone_number' => '+6012-3456789',
                ],
                'statutory' => [
                    'epf_member_no' => '21948102',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '930815145521',
                    'socso_category' => 'category_1_full',
                    'income_tax_no' => 'SG 281902410',
                    'tax_category' => 'married_non_working',
                    'number_of_children' => 2,
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'is_tax_resident' => true,
                ],
            ],

            // 2. Local Fixed-Term Contract Employee (Standard Statutory: 11% EPF / 13% ER, Cat 1 SOCSO, SKBBK, EIS, PCB)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $finDept?->id,
                    'employee_no' => 'MY-EMP-002',
                    'full_name' => 'Nurul Ain binti Mohd Faizal',
                    'nric_passport' => '970420-10-6188',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1997-04-20',
                    'joined_date' => '2024-06-01',
                    'basic_salary' => 4200.00,
                    'bank_name' => 'CIMB Bank Berhad',
                    'bank_account_no' => '7045123984',
                    'designation' => 'Finance & Accounting Specialist',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'contract',
                    'email' => 'nurul.ain@payflow.my',
                    'phone_number' => '+6013-8899123',
                ],
                'statutory' => [
                    'epf_member_no' => '33491820',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '970420106188',
                    'socso_category' => 'category_1_full',
                    'income_tax_no' => 'SG 349182090',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'is_tax_resident' => true,
                ],
            ],

            // 3. Foreign Contract Expatriate (2% EPF, Cat 2 SOCSO 1.25% ER only, EIS Exempt, Flat 30% Non-Resident Tax)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-003',
                    'full_name' => 'Alexander William Smith',
                    'nric_passport' => 'A98234120',
                    'citizenship' => 'foreign_worker',
                    'gender' => 'male',
                    'birth_date' => '1988-11-04',
                    'joined_date' => '2025-02-15',
                    'basic_salary' => 8500.00,
                    'bank_name' => 'Public Bank Berhad',
                    'bank_account_no' => '4481029381',
                    'designation' => 'Principal Security Specialist',
                    'employment_status' => 'active',
                    'employment_type' => 'contract_foreign',
                    'email' => 'alexander.smith@payflow.my',
                    'phone_number' => '+6011-2394810',
                ],
                'statutory' => [
                    'epf_member_no' => '55819201',
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 2.0,
                    'epf_employer_custom_rate' => 2.0,
                    'socso_member_no' => 'A98234120',
                    'socso_category' => 'category_2_injury_only',
                    'income_tax_no' => 'OG 991823019',
                    'tax_category' => 'single',
                    'number_of_children' => 1,
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'is_tax_resident' => false, // Flat 30% Non-Resident Tax
                ],
            ],

            // 4. Freelancer / Independent Consultant (Contract for Service - Gross Invoiced, Zero Statutory Withholdings)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $sbdDept?->id,
                    'employee_no' => 'MY-EMP-004',
                    'full_name' => 'Tan Wei Meng (Consultant)',
                    'nric_passport' => '890312-07-5319',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1989-03-12',
                    'joined_date' => '2025-09-01',
                    'basic_salary' => 5500.00,
                    'bank_name' => 'Hong Leong Bank Berhad',
                    'bank_account_no' => '1192837465',
                    'designation' => 'Digital Transformation Consultant',
                    'employment_status' => 'active',
                    'employment_type' => 'freelance_contract',
                    'email' => 'weimeng.tan@consultant.my',
                    'phone_number' => '+6016-7788990',
                ],
                'statutory' => [
                    'epf_member_no' => null,
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => null,
                    'socso_category' => 'category_1_full',
                    'income_tax_no' => 'SG 551928301',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'is_tax_resident' => true,
                ],
            ],

            // 5. Practical Intern (Student Trainee Stipend - Statutory Exempt)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $hraDept?->id,
                    'employee_no' => 'MY-EMP-005',
                    'full_name' => 'Siti Nurhaliza binti Kamaruddin',
                    'nric_passport' => '040918-08-6202',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '2004-09-18',
                    'joined_date' => '2026-05-01',
                    'basic_salary' => 1200.00,
                    'bank_name' => 'RHB Bank Berhad',
                    'bank_account_no' => '2140981273',
                    'designation' => 'People Operations Intern (UiTM)',
                    'employment_status' => 'probation',
                    'employment_type' => 'intern',
                    'email' => 'siti.intern@payflow.my',
                    'phone_number' => '+6019-3344556',
                ],
                'statutory' => [
                    'epf_member_no' => null,
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => null,
                    'socso_category' => 'category_1_full',
                    'income_tax_no' => null,
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'is_tax_resident' => true,
                ],
            ],

            // 6. Senior Citizen Advisor (Age 60+ Senior: 0% EE / 4% ER EPF, Cat 1 SOCSO, EIS Exempt)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $execDept?->id,
                    'employee_no' => 'MY-EMP-006',
                    'full_name' => 'Datuk Dr. Subramaniam a/l Murugan',
                    'nric_passport' => '620510-10-5491',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1962-05-10',
                    'joined_date' => '2023-01-01',
                    'basic_salary' => 12000.00,
                    'bank_name' => 'Malayan Banking Berhad (Maybank)',
                    'bank_account_no' => '514088991201',
                    'designation' => 'Executive Strategic Advisor',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'subramaniam@payflow.my',
                    'phone_number' => '+6012-9988776',
                ],
                'statutory' => [
                    'epf_member_no' => '10293847',
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 0.0,
                    'epf_employer_custom_rate' => 4.0,
                    'socso_member_no' => '620510105491',
                    'socso_category' => 'category_1_full',
                    'income_tax_no' => 'SG 109283740',
                    'tax_category' => 'married_non_working',
                    'number_of_children' => 0,
                    'is_eis_contributed' => false, // Exempt for age 60+ post-entrants
                    'is_skbbk_contributed' => true,
                    'is_tax_resident' => true,
                ],
            ],
        ];

        $leaveTypes = LeaveType::all();

        foreach ($employeesData as $data) {
            $employee = Employee::updateOrCreate(
                ['employee_no' => $data['employee']['employee_no']],
                $data['employee']
            );

            EmployeeStatutoryProfile::updateOrCreate(
                ['employee_id' => $employee->id],
                $data['statutory']
            );

            // Initialize default leave balances for current year
            foreach ($leaveTypes as $type) {
                EmployeeLeaveBalance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type_id' => $type->id,
                        'year' => (int) date('Y'),
                    ],
                    [
                        'total_entitled' => (float) $type->default_days_per_year,
                        'taken_days' => 0.0,
                        'pending_days' => 0.0,
                        'remaining_days' => (float) $type->default_days_per_year,
                    ]
                );
            }
        }
    }
}
