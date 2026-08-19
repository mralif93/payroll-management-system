<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Models\TaxFormEaRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HistoricalPayrollSeeder extends Seeder
{
    /**
     * Seed 12 months for 2025 and 7 months for 2026 (Jan - July 2026, until last month).
     */
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $employees = Employee::with(['salaryComponents.salaryComponent', 'statutoryProfile'])->get();
        if ($employees->isEmpty()) {
            return;
        }

        // Years and their completed months (2025: 12 months; 2026: Jan to July = 7 months)
        $schedules = [
            2025 => 12,
            2026 => 7,
        ];

        foreach ($schedules as $year => $maxMonth) {
            for ($month = 1; $month <= $maxMonth; $month++) {
                $batchNo = sprintf('RUN-%04d-%02d-PAY', $year, $month);
                $cutoff = sprintf('%04d-%02d-25', $year, $month);
                $payment = sprintf('%04d-%02d-28', $year, $month);

                $payrollRun = PayrollRun::updateOrCreate(
                    ['batch_no' => $batchNo],
                    [
                        'company_id' => $company->id,
                        'period_year' => (string) $year,
                        'period_month' => sprintf('%02d', $month),
                        'cutoff_date' => $cutoff,
                        'payment_date' => $payment,
                        'status' => 'approved',
                        'created_by' => 1,
                        'approved_by' => 1,
                        'approved_at' => $payment . ' 17:00:00',
                    ]
                );

                $totalGross = 0;
                $totalEeStat = 0;
                $totalErStat = 0;
                $totalNet = 0;

                foreach ($employees as $employee) {
                    $basic = (float) $employee->basic_salary;
                    $allowances = (float) $employee->salaryComponents->where('salaryComponent.type', 'allowance')->sum('amount');
                    $gross = $basic + $allowances;
                    $empType = $employee->employment_type ?? 'permanent';

                    // Statutory calculations
                    if ($empType === 'freelance_contract' || $empType === 'intern') {
                        $epfEe = 0.00;
                        $epfEr = 0.00;
                        $socsoEe = 0.00;
                        $socsoEr = 0.00;
                        $eisEe = 0.00;
                        $eisEr = 0.00;
                        $pcb = 0.00;
                    } elseif ($empType === 'contract_foreign' || $employee->citizenship === 'foreign_worker') {
                        $epfEe = round($gross * 0.02, 2);
                        $epfEr = round($gross * 0.02, 2);
                        $socsoEe = 0.00;
                        $socsoEr = min(round($gross * 0.0125, 2), 74.38);
                        $eisEe = 0.00;
                        $eisEr = 0.00;
                        $pcb = round($gross * 0.30, 2); // Flat 30%
                    } elseif ($employee->birth_date && \Carbon\Carbon::parse($employee->birth_date)->age >= 60) {
                        $epfEe = 0.00;
                        $epfEr = round($gross * 0.04, 2);
                        $socsoEe = 29.75;
                        $socsoEr = 104.15;
                        $eisEe = 0.00;
                        $eisEr = 0.00;
                        $pcb = \App\Http\Controllers\Admin\PayrollRunController::calculatePcb($gross, 0.00, true);
                    } else {
                        // Standard Local
                        $epfValues = \App\Http\Controllers\Admin\PayrollRunController::calculateEpf($gross, 'standard_11', 0, 0, false);
                        $epfEe = $epfValues['ee'];
                        $epfEr = $epfValues['er'];
                        $socsoValues = \App\Http\Controllers\Admin\PayrollRunController::calculateSocso($gross, true, 'category_1_full');
                        $socsoEe = $socsoValues['ee'];
                        $socsoEr = $socsoValues['er'];
                        $eisValues = \App\Http\Controllers\Admin\PayrollRunController::calculateEis($gross, true);
                        $eisEe = $eisValues['ee'];
                        $eisEr = $eisValues['er'];
                        $pcb = \App\Http\Controllers\Admin\PayrollRunController::calculatePcb($gross, $epfEe, true);
                    }

                    $deductions = $epfEe + $socsoEe + $eisEe + $pcb;
                    $net = $gross - $deductions;

                    PayrollItem::updateOrCreate(
                        [
                            'payroll_run_id' => $payrollRun->id,
                            'employee_id' => $employee->id,
                        ],
                        [
                            'basic_salary' => $basic,
                            'allowances_total' => $allowances,
                            'gross_salary' => $gross,
                            'epf_subject_wages' => $gross,
                            'socso_subject_wages' => min($gross, 6000.00),
                            'eis_subject_wages' => min($gross, 6000.00),
                            'pcb_subject_wages' => $gross,
                            'epf_employee' => $epfEe,
                            'socso_employee' => $socsoEe,
                            'eis_employee' => $eisEe,
                            'pcb_amount' => $pcb,
                            'total_employee_deductions' => $deductions,
                            'epf_employer' => $epfEr,
                            'socso_employer' => $socsoEr,
                            'eis_employer' => $eisEr,
                            'total_employer_contributions' => $epfEr + $socsoEr + $eisEr,
                            'net_salary' => $net,
                            'payslip_token' => Str::random(32),
                        ]
                    );

                    $totalGross += $gross;
                    $totalEeStat += $deductions;
                    $totalErStat += ($epfEr + $socsoEr + $eisEr);
                    $totalNet += $net;
                }

                $payrollRun->update([
                    'total_headcount' => $employees->count(),
                    'total_gross_amount' => $totalGross,
                    'total_statutory_employee' => $totalEeStat,
                    'total_statutory_employer' => $totalErStat,
                    'total_net_disbursement' => $totalNet,
                ]);
            }

            // Auto-compile Form EA records for each year
            foreach ($employees as $employee) {
                $items = PayrollItem::where('employee_id', $employee->id)
                    ->whereHas('payrollRun', function ($q) use ($year) {
                        $q->where('period_year', (string) $year);
                    })->get();

                $grossWages = $items->sum('gross_salary');
                $bonus = 0.00;
                $pcb = $items->sum('pcb_amount');
                $zakat = $items->sum('zakat_amount');
                $epf = $items->sum('epf_employee');
                $socso = $items->sum('socso_employee') + $items->sum('skbbk_employee');
                $eis = $items->sum('eis_employee');

                TaxFormEaRecord::updateOrCreate(
                    ['employee_id' => $employee->id, 'tax_year' => (string) $year],
                    [
                        'serial_no' => "EA-{$year}-" . $employee->employee_no,
                        'employer_e_no' => $company->tax_no ?? 'E 9876543200',
                        'gross_salary_wages' => $grossWages,
                        'fees_commission_bonus' => $bonus,
                        'total_pcb_mtd' => $pcb,
                        'total_zakat_paid' => $zakat,
                        'total_epf_employee' => $epf,
                        'total_socso_employee' => $socso,
                        'total_eis_employee' => $eis,
                    ]
                );
            }
        }
    }
}
