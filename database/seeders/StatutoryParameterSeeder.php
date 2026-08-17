<?php

namespace Database\Seeders;

use App\Models\StatutoryParameter;
use Illuminate\Database\Seeder;

class StatutoryParameterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. KWSP / EPF Rates & Rules (Employees Provident Fund Act 1991)
        StatutoryParameter::updateOrCreate(
            ['category' => 'epf', 'parameter_key' => 'standard_rules', 'effective_from' => '2024-01-01'],
            [
                'name' => 'KWSP / EPF Statutory Contribution Rates',
                'description' => 'EPF Act 1991 Third Schedule standard employee & employer percentages and wage thresholds.',
                'effective_to' => '9999-12-31',
                'is_active' => true,
                'reference_gazette' => 'P.U. (A) EPF Act 1991 Third Schedule',
                'value_payload' => [
                    'salary_threshold' => 5000.00,
                    'standard_employee_rate' => 0.11,
                    'voluntary_reduced_employee_rate' => 0.09,
                    'employer_rate_low_wage' => 0.13,     // <= RM5,000 basic
                    'employer_rate_high_wage' => 0.12,    // > RM5,000 basic
                    'senior_citizen_employee_rate' => 0.00, // Age >= 60
                    'senior_citizen_employer_rate' => 0.04,
                    'foreign_worker_employee_rate' => 0.02,
                    'foreign_worker_employer_rate' => 0.02,
                    'max_pcb_tax_relief' => 4000.00,
                ],
            ]
        );

        // 2. PERKESO / SOCSO (Act 4) & June 2026 SKBBK Scheme
        StatutoryParameter::updateOrCreate(
            ['category' => 'socso', 'parameter_key' => 'socso_schedule_2026', 'effective_from' => '2026-06-01'],
            [
                'name' => 'PERKESO SOCSO & SKBBK (Lindung 24 Jam) Schedule',
                'description' => 'Employees Social Security Act 1969 & Skim Kemalangan Bukan Bencana Kerja (SKBBK) tiered tables.',
                'effective_to' => '9999-12-31',
                'is_active' => true,
                'reference_gazette' => 'Warta Kerajaan PERKESO SKBBK 2026',
                'value_payload' => [
                    'wage_ceiling' => 6000.00,
                    'skbbk_effective_date' => '2026-06-01',
                    'category_1' => [
                        'employer_rate_percentage' => 0.0175,
                        'employee_base_percentage' => 0.005,
                    ],
                    'category_2' => [
                        'employer_rate_percentage' => 0.0125,
                        'employee_base_percentage' => 0.00,
                    ],
                    'sample_tiers' => [
                        ['min' => 1900.01, 'max' => 2000.00, 'socso_er' => 34.15, 'socso_ee' => 9.90, 'skbbk_ee' => 14.50, 'total_ee' => 24.40],
                        ['min' => 2000.01, 'max' => 2100.00, 'socso_er' => 35.85, 'socso_ee' => 10.40, 'skbbk_ee' => 15.20, 'total_ee' => 25.60],
                        ['min' => 5900.01, 'max' => 6000.00, 'socso_er' => 104.15, 'socso_ee' => 29.90, 'skbbk_ee' => 43.50, 'total_ee' => 73.40],
                    ],
                ],
            ]
        );

        // 3. SIP / EIS Schedule (Employment Insurance System Act 2017)
        StatutoryParameter::updateOrCreate(
            ['category' => 'eis', 'parameter_key' => 'standard_schedule', 'effective_from' => '2024-01-01'],
            [
                'name' => 'SIP / EIS Contribution Schedule',
                'description' => 'Employment Insurance System Act 2017 0.2% Employee & 0.2% Employer wage brackets up to RM6,000 ceiling.',
                'effective_to' => '9999-12-31',
                'is_active' => true,
                'reference_gazette' => 'Akta Sistem Insurans Pekerjaan 2017 (Akta 800)',
                'value_payload' => [
                    'wage_ceiling' => 6000.00,
                    'employee_rate' => 0.002,
                    'employer_rate' => 0.002,
                    'sample_tiers' => [
                        ['min' => 1900.01, 'max' => 2000.00, 'eis_er' => 3.90, 'eis_ee' => 3.90],
                        ['min' => 2000.01, 'max' => 2100.00, 'eis_er' => 4.10, 'eis_ee' => 4.10],
                        ['min' => 5900.01, 'max' => 6000.00, 'eis_er' => 11.90, 'eis_ee' => 11.90],
                    ],
                ],
            ]
        );

        // 4. LHDN PCB / MTD Computerised Brackets (Income Tax Act 1967)
        StatutoryParameter::updateOrCreate(
            ['category' => 'pcb', 'parameter_key' => 'tax_reliefs_and_brackets', 'effective_from' => '2024-01-01'],
            [
                'name' => 'LHDN Monthly Tax Deduction (PCB) Brackets & Reliefs',
                'description' => 'Kaedah-Kaedah Cukai Pendapatan (Potongan Daripada Saraan) Computerised PCB Calculation Tables.',
                'effective_to' => '9999-12-31',
                'is_active' => true,
                'reference_gazette' => 'LHDN PCB Computerised Formula Specifications',
                'value_payload' => [
                    'individual_relief' => 9000.00,
                    'spouse_non_working_relief' => 4000.00,
                    'child_relief_per_child' => 2000.00,
                    'disabled_individual_relief' => 6000.00,
                    'disabled_spouse_relief' => 5000.00,
                    'epf_annual_max_relief' => 4000.00,
                    'tax_brackets' => [
                        ['min' => 0, 'max' => 5000, 'base_tax' => 0, 'rate' => 0.00],
                        ['min' => 5001, 'max' => 20000, 'base_tax' => 0, 'rate' => 0.01],
                        ['min' => 20001, 'max' => 35000, 'base_tax' => 150, 'rate' => 0.03],
                        ['min' => 35001, 'max' => 50000, 'base_tax' => 600, 'rate' => 0.06],
                        ['min' => 50001, 'max' => 70000, 'base_tax' => 1500, 'rate' => 0.11],
                        ['min' => 70001, 'max' => 100000, 'base_tax' => 3700, 'rate' => 0.19],
                        ['min' => 100001, 'max' => 400000, 'base_tax' => 9400, 'rate' => 0.25],
                        ['min' => 400001, 'max' => 600000, 'base_tax' => 84400, 'rate' => 0.26],
                        ['min' => 600001, 'max' => 2000000, 'base_tax' => 136400, 'rate' => 0.28],
                        ['min' => 2000001, 'max' => 999999999, 'base_tax' => 528400, 'rate' => 0.30],
                    ],
                ],
            ]
        );

        // 5. HRD Corp Levy Rules
        StatutoryParameter::updateOrCreate(
            ['category' => 'hrd', 'parameter_key' => 'hrd_levy_rates', 'effective_from' => '2024-01-01'],
            [
                'name' => 'HRD Corp Mandatory & Optional Levy Rules',
                'description' => 'Pembangunan Sumber Manusia Berhad Act 2001 employer headcount levy multipliers.',
                'effective_to' => '9999-12-31',
                'is_active' => true,
                'reference_gazette' => 'PSMB Act 2001 First Schedule',
                'value_payload' => [
                    'mandatory_staff_threshold' => 10,
                    'mandatory_rate' => 0.01,
                    'optional_staff_threshold' => 5,
                    'optional_rate' => 0.005,
                ],
            ]
        );
    }
}
