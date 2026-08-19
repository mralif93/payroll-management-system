<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeSalaryComponent;
use App\Models\EmployeeStatutoryProfile;
use App\Models\LeaveType;
use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed 15 realistic Malaysian employees covering all contract classifications, departments, and statutory profiles.
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

        // Fetch standard salary components
        $attAllow = SalaryComponent::where('code', 'ATT_ALLOW')->first();
        $housingAllow = SalaryComponent::where('code', 'HOUSING_ALLOW')->first();
        $travelAllow = SalaryComponent::where('code', 'TRAVEL_ALLOW')->first();
        $phoneAllow = SalaryComponent::where('code', 'PHONE_ALLOW')->first();
        $mealAllow = SalaryComponent::where('code', 'MEAL_ALLOW')->first();

        $employeesData = [
            // 1. Muhammad Alif - Lead Software Architect (Permanent Local)
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
                    'phone_number' => '+60 12-345 6789',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-8829104',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '930815145521',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 28910482010',
                    'tax_category' => 'married_working',
                    'number_of_children' => 2,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $housingAllow?->id, 'amount' => 500.00, 'notes' => 'Monthly Living & Housing Allowance (COLA)'],
                    ['component_id' => $phoneAllow?->id, 'amount' => 150.00, 'notes' => 'Mobile & Data Allowance'],
                ],
            ],

            // 2. Nurul Ain - Senior Finance Specialist (Contract Local)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $finDept?->id,
                    'employee_no' => 'MY-EMP-002',
                    'full_name' => 'Nurul Ain binti Mohd Faizal',
                    'nric_passport' => '960322-10-6142',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1996-03-22',
                    'joined_date' => '2023-04-01',
                    'basic_salary' => 4200.00,
                    'bank_name' => 'CIMB Bank Berhad',
                    'bank_account_no' => '7049182301',
                    'designation' => 'Senior Finance Specialist',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'contract',
                    'email' => 'nurul.ain@payflow.my',
                    'phone_number' => '+60 17-889 0123',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-7491028',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '960322106142',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 94810294020',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $travelAllow?->id, 'amount' => 300.00, 'notes' => 'Monthly Travel & Petrol Subsidy'],
                    ['component_id' => $attAllow?->id, 'amount' => 200.00, 'notes' => 'Perfect Attendance Allowance'],
                ],
            ],

            // 3. Alexander Smith - Head of Cyber Security (Expat Foreign Worker)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-003',
                    'full_name' => 'Alexander William Smith',
                    'nric_passport' => 'GBR-P9982014',
                    'citizenship' => 'foreign_worker',
                    'gender' => 'male',
                    'birth_date' => '1988-11-04',
                    'joined_date' => '2024-02-15',
                    'basic_salary' => 9500.00,
                    'bank_name' => 'HSBC Bank Malaysia Berhad',
                    'bank_account_no' => '30291840192',
                    'designation' => 'Head of Cyber Security',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'contract_foreign',
                    'email' => 'alexander.smith@payflow.my',
                    'phone_number' => '+60 11-2345 6789',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9948102',
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 2.0,
                    'epf_employer_custom_rate' => 2.0,
                    'socso_member_no' => 'GBRP9982014',
                    'socso_category' => 'category_2_injury_only',
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'income_tax_no' => 'OG 48192039100',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => false,
                ],
                'allowances' => [
                    ['component_id' => $housingAllow?->id, 'amount' => 600.00, 'notes' => 'Expatriate Living Allowance'],
                ],
            ],

            // 4. Tan Wei Meng - Principal Cloud Architect (Freelancer Consultant)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-004',
                    'full_name' => 'Tan Wei Meng',
                    'nric_passport' => '910512-07-5339',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1991-05-12',
                    'joined_date' => '2024-01-01',
                    'basic_salary' => 5500.00,
                    'bank_name' => 'Public Bank Berhad',
                    'bank_account_no' => '4829103910',
                    'designation' => 'Principal Cloud Consultant',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'freelance_contract',
                    'email' => 'weimeng.tan@consultant.my',
                    'phone_number' => '+60 16-778 9901',
                ],
                'statutory' => [
                    'epf_member_no' => null,
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 0.0,
                    'epf_employer_custom_rate' => 0.0,
                    'socso_member_no' => null,
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'income_tax_no' => 'SG 77819203910',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [],
            ],

            // 5. Siti Nurhaliza - HR & Talent Intern
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $hraDept?->id,
                    'employee_no' => 'MY-EMP-005',
                    'full_name' => 'Siti Nurhaliza binti Kamaruddin',
                    'nric_passport' => '030910-14-6622',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '2003-09-10',
                    'joined_date' => '2024-06-01',
                    'basic_salary' => 1200.00,
                    'bank_name' => 'Bank Islam Malaysia Berhad',
                    'bank_account_no' => '1209182390192',
                    'designation' => 'Talent Acquisition Intern',
                    'employment_status' => 'probation',
                    'employment_type' => 'intern',
                    'email' => 'siti.nurhaliza@payflow.my',
                    'phone_number' => '+60 13-998 1234',
                ],
                'statutory' => [
                    'epf_member_no' => null,
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 0.0,
                    'epf_employer_custom_rate' => 0.0,
                    'socso_member_no' => null,
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => false,
                    'income_tax_no' => null,
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $mealAllow?->id, 'amount' => 200.00, 'notes' => 'Monthly Meal Allowance'],
                ],
            ],

            // 6. Datuk Dr. Subramaniam - Chief Technical Advisor (Senior 60+)
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $execDept?->id,
                    'employee_no' => 'MY-EMP-006',
                    'full_name' => 'Datuk Dr. Subramaniam a/l Ramasamy',
                    'nric_passport' => '620418-08-5119',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1962-04-18',
                    'joined_date' => '2020-01-01',
                    'basic_salary' => 14000.00,
                    'bank_name' => 'RHB Bank Berhad',
                    'bank_account_no' => '214910283019',
                    'designation' => 'Chief Technical Advisor',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'subramaniam.ramasamy@payflow.my',
                    'phone_number' => '+60 19-332 1100',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-1102938',
                    'epf_rate_type' => 'custom',
                    'epf_employee_custom_rate' => 0.0,
                    'epf_employer_custom_rate' => 4.0,
                    'socso_member_no' => '620418085119',
                    'socso_category' => 'category_2_injury_only',
                    'is_eis_contributed' => false,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 10293819200',
                    'tax_category' => 'married_non_working',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $housingAllow?->id, 'amount' => 500.00, 'notes' => 'Executive COLA Allowance'],
                ],
            ],

            // 7. Faridah Hanum - Head of Human Resources
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $hraDept?->id,
                    'employee_no' => 'MY-EMP-007',
                    'full_name' => 'Faridah Hanum binti Osman',
                    'nric_passport' => '890714-03-5120',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1989-07-14',
                    'joined_date' => '2021-03-15',
                    'basic_salary' => 7200.00,
                    'bank_name' => 'Malayan Banking Berhad (Maybank)',
                    'bank_account_no' => '164188203912',
                    'designation' => 'Head of Human Resources',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'faridah.hanum@payflow.my',
                    'phone_number' => '+60 12-881 2940',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-8920192',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '890714035120',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 89019283010',
                    'tax_category' => 'married_working',
                    'number_of_children' => 3,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $phoneAllow?->id, 'amount' => 150.00, 'notes' => 'Mobile & Data Allowance'],
                    ['component_id' => $travelAllow?->id, 'amount' => 300.00, 'notes' => 'Travel Allowance'],
                ],
            ],

            // 8. Kenneth Lee - Senior DevOps Engineer
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-008',
                    'full_name' => 'Kenneth Lee Chun Kit',
                    'nric_passport' => '941205-10-5881',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1994-12-05',
                    'joined_date' => '2022-08-01',
                    'basic_salary' => 5800.00,
                    'bank_name' => 'Hong Leong Bank Berhad',
                    'bank_account_no' => '02910392019',
                    'designation' => 'Senior DevOps Engineer',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'kenneth.lee@payflow.my',
                    'phone_number' => '+60 16-229 1049',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9481920',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '941205105881',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 94810293010',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $housingAllow?->id, 'amount' => 400.00, 'notes' => 'COLA Allowance'],
                ],
            ],

            // 9. Priya Devi - Senior QA Automation Engineer
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-009',
                    'full_name' => 'Priya Devi a/p Murali',
                    'nric_passport' => '970519-14-6102',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1997-05-19',
                    'joined_date' => '2023-02-15',
                    'basic_salary' => 4800.00,
                    'bank_name' => 'Maybank',
                    'bank_account_no' => '114012948102',
                    'designation' => 'Senior QA Automation Engineer',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'priya.murali@payflow.my',
                    'phone_number' => '+60 14-889 2019',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9710293',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '970519146102',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 97102938100',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $attAllow?->id, 'amount' => 200.00, 'notes' => 'Monthly Attendance Allowance'],
                ],
            ],

            // 10. Ahmad Haziq - Business Development Manager
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $sbdDept?->id,
                    'employee_no' => 'MY-EMP-010',
                    'full_name' => 'Ahmad Haziq bin Zakaria',
                    'nric_passport' => '920111-01-5233',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1992-01-11',
                    'joined_date' => '2022-05-10',
                    'basic_salary' => 6000.00,
                    'bank_name' => 'CIMB Bank',
                    'bank_account_no' => '7019283019',
                    'designation' => 'Business Development Manager',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'haziq.zakaria@payflow.my',
                    'phone_number' => '+60 17-339 2011',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9201928',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '920111015233',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 92019283910',
                    'tax_category' => 'married_working',
                    'number_of_children' => 1,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $travelAllow?->id, 'amount' => 500.00, 'notes' => 'Client Engagement Travel Allowance'],
                    ['component_id' => $phoneAllow?->id, 'amount' => 150.00, 'notes' => 'Mobile & Data Allowance'],
                ],
            ],

            // 11. Chloe Wong - UI/UX Product Designer
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-011',
                    'full_name' => 'Chloe Wong Xin Yi',
                    'nric_passport' => '980820-14-5390',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1998-08-20',
                    'joined_date' => '2023-07-01',
                    'basic_salary' => 4500.00,
                    'bank_name' => 'Maybank',
                    'bank_account_no' => '164192039102',
                    'designation' => 'Lead UI/UX Designer',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'chloe.wong@payflow.my',
                    'phone_number' => '+60 12-990 1239',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9801928',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '980820145390',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 98019283019',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $housingAllow?->id, 'amount' => 300.00, 'notes' => 'Living Allowance'],
                ],
            ],

            // 12. Rajesh Kumar - Senior Systems Administrator
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $techDept?->id,
                    'employee_no' => 'MY-EMP-012',
                    'full_name' => 'Rajesh Kumar a/l Loganathan',
                    'nric_passport' => '901015-08-5421',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1990-10-15',
                    'joined_date' => '2021-11-01',
                    'basic_salary' => 5200.00,
                    'bank_name' => 'AmBank (M) Berhad',
                    'bank_account_no' => '888102930192',
                    'designation' => 'Senior Systems Administrator',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'rajesh.kumar@payflow.my',
                    'phone_number' => '+60 18-339 1049',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9019283',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '901015085421',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 90192830192',
                    'tax_category' => 'married_working',
                    'number_of_children' => 2,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $attAllow?->id, 'amount' => 200.00, 'notes' => 'Attendance Allowance'],
                ],
            ],

            // 13. Nur Zulaikha - Payroll & Statutory Officer
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $finDept?->id,
                    'employee_no' => 'MY-EMP-013',
                    'full_name' => 'Nur Zulaikha binti Razak',
                    'nric_passport' => '950618-06-5324',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1995-06-18',
                    'joined_date' => '2023-09-01',
                    'basic_salary' => 3800.00,
                    'bank_name' => 'Bank Muamalat Malaysia Berhad',
                    'bank_account_no' => '14019283019',
                    'designation' => 'Payroll & Statutory Officer',
                    'employment_status' => 'confirmed',
                    'employment_type' => 'permanent',
                    'email' => 'zulaikha.razak@payflow.my',
                    'phone_number' => '+60 19-441 9021',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9501928',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '950618065324',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 95019283019',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $travelAllow?->id, 'amount' => 200.00, 'notes' => 'Travel Allowance'],
                ],
            ],

            // 14. Jason Lee - Enterprise Account Executive
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $sbdDept?->id,
                    'employee_no' => 'MY-EMP-014',
                    'full_name' => 'Jason Lee Kai Jun',
                    'nric_passport' => '970208-14-5991',
                    'citizenship' => 'malaysian',
                    'gender' => 'male',
                    'birth_date' => '1997-02-08',
                    'joined_date' => '2024-03-01',
                    'basic_salary' => 4000.00,
                    'bank_name' => 'Alliance Bank Malaysia Berhad',
                    'bank_account_no' => '12019283019',
                    'designation' => 'Enterprise Account Executive',
                    'employment_status' => 'probation',
                    'employment_type' => 'permanent',
                    'email' => 'jason.lee@payflow.my',
                    'phone_number' => '+60 12-771 9029',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9701928',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '970208145991',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 97019283010',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $travelAllow?->id, 'amount' => 400.00, 'notes' => 'Sales Travel Allowance'],
                    ['component_id' => $phoneAllow?->id, 'amount' => 150.00, 'notes' => 'Mobile Phone Allowance'],
                ],
            ],

            // 15. Hanim Mastura - Executive Administrative Assistant
            [
                'employee' => [
                    'company_id' => $company->id,
                    'department_id' => $hraDept?->id,
                    'employee_no' => 'MY-EMP-015',
                    'full_name' => 'Hanim Mastura binti Sulaiman',
                    'nric_passport' => '990412-11-5432',
                    'citizenship' => 'malaysian',
                    'gender' => 'female',
                    'birth_date' => '1999-04-12',
                    'joined_date' => '2024-04-15',
                    'basic_salary' => 3200.00,
                    'bank_name' => 'Bank Simpanan Nasional (BSN)',
                    'bank_account_no' => '101928301928',
                    'designation' => 'Executive Admin Officer',
                    'employment_status' => 'probation',
                    'employment_type' => 'permanent',
                    'email' => 'hanim.mastura@payflow.my',
                    'phone_number' => '+60 13-559 1029',
                ],
                'statutory' => [
                    'epf_member_no' => 'EPF-9901928',
                    'epf_rate_type' => 'standard_11',
                    'socso_member_no' => '990412115432',
                    'socso_category' => 'category_1_full',
                    'is_eis_contributed' => true,
                    'is_skbbk_contributed' => true,
                    'income_tax_no' => 'SG 99019283019',
                    'tax_category' => 'single',
                    'number_of_children' => 0,
                    'is_tax_resident' => true,
                ],
                'allowances' => [
                    ['component_id' => $attAllow?->id, 'amount' => 150.00, 'notes' => 'Attendance Allowance'],
                    ['component_id' => $mealAllow?->id, 'amount' => 150.00, 'notes' => 'Meal Subsidy'],
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

            // Attach mapped allowances
            if (!empty($data['allowances'])) {
                foreach ($data['allowances'] as $allowance) {
                    if (!empty($allowance['component_id'])) {
                        EmployeeSalaryComponent::updateOrCreate(
                            [
                                'employee_id' => $employee->id,
                                'salary_component_id' => $allowance['component_id'],
                            ],
                            [
                                'amount' => $allowance['amount'],
                                'effective_from' => '2024-01-01',
                                'effective_to' => '9999-12-31',
                                'is_recurring' => true,
                                'notes' => $allowance['notes'] ?? null,
                            ]
                        );
                    }
                }
            }

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
