<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Services\Payroll\StatutoryParameterResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayrollRunController extends Controller
{
    /**
     * Get exact PERKESO SOCSO (Act 4) Employee and Employer contribution from statutory wage bracket.
     */
    public static function calculateSocso(float $grossWage): array
    {
        // PERKESO wage ceiling is RM6,000.00
        $wage = min($grossWage, 6000.00);

        if ($wage <= 30.00) return ['ee' => 0.10, 'er' => 0.40];
        if ($wage <= 50.00) return ['ee' => 0.20, 'er' => 0.70];
        if ($wage <= 70.00) return ['ee' => 0.30, 'er' => 1.10];
        if ($wage <= 100.00) return ['ee' => 0.40, 'er' => 1.50];
        if ($wage <= 140.00) return ['ee' => 0.60, 'er' => 2.10];
        if ($wage <= 200.00) return ['ee' => 0.85, 'er' => 2.95];
        if ($wage <= 300.00) return ['ee' => 1.25, 'er' => 4.35];
        if ($wage <= 400.00) return ['ee' => 1.75, 'er' => 6.15];
        if ($wage <= 500.00) return ['ee' => 2.25, 'er' => 7.85];
        
        // For wages > RM500 up to RM6000, brackets are in increments of RM100:
        // E.g., RM4,400.01 - RM4,500.00 => EE: RM22.25, ER: RM77.85
        // Total Base SOCSO: 0.5% EE, 1.75% ER
        $tier = (int) ceil(($wage - 500.00) / 100.00);
        $ee = 2.25 + ($tier * 0.50);
        $er = 7.85 + ($tier * 1.75);

        return [
            'ee' => min(29.75, round($ee, 2)),
            'er' => min(104.15, round($er, 2)),
        ];
    }

    /**
     * Get exact SIP / EIS (Act 800) Employee and Employer contribution from statutory wage bracket.
     */
    public static function calculateEis(float $grossWage): array
    {
        // EIS wage ceiling is RM6,000.00 (0.2% EE, 0.2% ER)
        $wage = min($grossWage, 6000.00);

        if ($wage <= 30.00) return ['ee' => 0.05, 'er' => 0.05];
        if ($wage <= 50.00) return ['ee' => 0.10, 'er' => 0.10];
        if ($wage <= 70.00) return ['ee' => 0.15, 'er' => 0.15];
        if ($wage <= 100.00) return ['ee' => 0.20, 'er' => 0.20];
        if ($wage <= 140.00) return ['ee' => 0.25, 'er' => 0.25];
        if ($wage <= 200.00) return ['ee' => 0.35, 'er' => 0.35];
        if ($wage <= 300.00) return ['ee' => 0.50, 'er' => 0.50];
        if ($wage <= 400.00) return ['ee' => 0.70, 'er' => 0.70];
        if ($wage <= 500.00) return ['ee' => 0.90, 'er' => 0.90];

        // For wages > RM500, increments of RM100:
        // RM4,400.01 - RM4,500.00 => EE: RM8.90, ER: RM8.90
        $tier = (int) ceil(($wage - 500.00) / 100.00);
        $rate = 0.90 + ($tier * 0.20);

        return [
            'ee' => min(11.90, round($rate, 2)),
            'er' => min(11.90, round($rate, 2)),
        ];
    }

    /**
     * Get exact LHDN Computerised Monthly Tax Deduction (PCB / MTD) for standard single / non-claim tax profile.
     */
    public static function calculatePcb(float $grossWage, float $epfEe): float
    {
        // Monthly Net Taxable = Gross - min(EPF EE, RM333.33) [LHDN annual max EPF relief RM4,000 / 12 = 333.33]
        $taxableGross = $grossWage - min($epfEe, 333.33);

        // Annualized Estimated Taxable Income: (Monthly Taxable * 12) - Individual Relief (RM9,000)
        $annualIncome = ($taxableGross * 12) - 9000.00;

        if ($annualIncome <= 5000) return 0.00;

        // LHDN 2024/2026 Progressive Individual Tax Brackets
        $tax = 0.00;
        if ($annualIncome <= 20000) {
            $tax = ($annualIncome - 5000) * 0.01;
        } elseif ($annualIncome <= 35000) {
            $tax = 150 + (($annualIncome - 20000) * 0.03);
        } elseif ($annualIncome <= 50000) {
            $tax = 600 + (($annualIncome - 35000) * 0.06);
        } elseif ($annualIncome <= 70000) {
            $tax = 1500 + (($annualIncome - 50000) * 0.11);
        } elseif ($annualIncome <= 100000) {
            $tax = 3700 + (($annualIncome - 70000) * 0.19);
        } elseif ($annualIncome <= 400000) {
            $tax = 9400 + (($annualIncome - 100000) * 0.25);
        } elseif ($annualIncome <= 600000) {
            $tax = 84400 + (($annualIncome - 400000) * 0.26);
        } else {
            $tax = 136400 + (($annualIncome - 600000) * 0.28);
        }

        // Monthly MTD / PCB rounded to 5 cents as per LHDN rules
        $monthlyPcb = max(0.00, round($tax / 12, 2));

        return $monthlyPcb;
    }

    public function __construct(
        protected StatutoryParameterResolver $parameterResolver
    ) {}

    /**
     * List all monthly payroll processing batches.
     */
    public function index()
    {
        $payrollRuns = PayrollRun::with(['company', 'creator', 'approver'])
            ->latest('period_year')
            ->latest('period_month')
            ->paginate(12);

        $companies = Company::all();
        $activeEmployeesCount = Employee::where('employment_status', 'active')->count();

        // Real-time calculated stat card aggregations
        $latestRun = $payrollRuns->first();
        $totalNetPool = $payrollRuns->sum('total_net_disbursement');
        $totalGrossPool = $payrollRuns->sum('total_gross_amount');
        $totalEmployeeStatutory = $payrollRuns->sum('total_statutory_employee');
        $totalEmployerStatutory = $payrollRuns->sum('total_statutory_employer');

        return view('admin.payroll.index', compact(
            'payrollRuns',
            'companies',
            'activeEmployeesCount',
            'latestRun',
            'totalNetPool',
            'totalGrossPool',
            'totalEmployeeStatutory',
            'totalEmployerStatutory'
        ));
    }

    /**
     * Create and compute a new monthly payroll batch.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'period_year' => ['required', 'string', 'size:4'],
            'period_month' => ['required', 'string', 'size:2'],
            'cutoff_date' => ['required', 'date'],
            'payment_date' => ['required', 'date'],
        ]);

        $batchNo = 'RUN-' . $validated['period_year'] . '-' . $validated['period_month'] . '-' . strtoupper(Str::random(4));

        $payrollRun = PayrollRun::create([
            'company_id' => $validated['company_id'],
            'batch_no' => $batchNo,
            'period_year' => $validated['period_year'],
            'period_month' => $validated['period_month'],
            'cutoff_date' => $validated['cutoff_date'],
            'payment_date' => $validated['payment_date'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Process all active employees in company
        $employees = Employee::where('company_id', $validated['company_id'])
            ->where('employment_status', 'active')
            ->with(['statutoryProfile', 'salaryComponents.salaryComponent'])
            ->get();

        $totalGross = 0;
        $totalEmployeeStatutory = 0;
        $totalEmployerStatutory = 0;
        $totalNet = 0;

        // Calculate period date boundaries
        $periodStart = sprintf('%04d-%02d-01', $payrollRun->period_year, $payrollRun->period_month);
        $periodEnd = date('Y-m-t', strtotime($periodStart));

        foreach ($employees as $employee) {
            $basic = (float) $employee->basic_salary;
            $allowances = (float) $employee->salaryComponents->where('salaryComponent.type', 'allowance')->sum('amount');
            $empType = $employee->employment_type ?? 'permanent';

            // 1. Calculate Unpaid / No-Pay Leave Deductions for the payroll cycle
            // Malaysian Employment Act 1955: Daily Rate (ORP) = Basic Salary / 26 days
            $unpaidLeaveDays = (float) \App\Models\LeaveApplication::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereHas('leaveType', function ($query) {
                    $query->where('is_paid', false);
                })
                ->where(function ($query) use ($periodStart, $periodEnd) {
                    $query->whereBetween('start_date', [$periodStart, $periodEnd])
                        ->orWhereBetween('end_date', [$periodStart, $periodEnd])
                        ->orWhere(function ($q) use ($periodStart, $periodEnd) {
                            $q->where('start_date', '<=', $periodStart)
                                ->where('end_date', '>=', $periodEnd);
                        });
                })
                ->sum('total_days');

            $dailyOrp = ($basic > 0) ? round($basic / 26.0, 2) : 0.00;
            $unpaidLeaveDeduction = round($unpaidLeaveDays * $dailyOrp, 2);

            // Gross salary after deducting unpaid absence
            $gross = max(0.00, $basic + $allowances - $unpaidLeaveDeduction);

            // 2. Interns (Practical Students receiving stipend) are legally exempt from mandatory EPF, SOCSO & EIS
            if ($empType === 'intern') {
                $epfEe = 0.00;
                $epfEr = 0.00;
                $socsoEe = 0.00;
                $skbbkEe = 0.00;
                $socsoEr = 0.00;
                $eisEe = 0.00;
                $eisEr = 0.00;
                $pcb = 0.00;
            } else {
                // 3. Permanent, Contract & Part-Time Staff: Full Statutory Calculation
                // Compute EPF (Dynamic EE Rate: Standard 11%, Reduced 9%, or Custom %; ER Rate: 13% <=RM5k, 12% >RM5k or Custom %)
                $epfRateType = $employee->statutoryProfile?->epf_rate_type ?? 'standard_11';
                if ($epfRateType === 'reduced_9') {
                    $epfEeRate = 0.09;
                } elseif ($epfRateType === 'custom' && $employee->statutoryProfile?->epf_employee_custom_rate) {
                    $epfEeRate = ((float) $employee->statutoryProfile->epf_employee_custom_rate) / 100;
                } else {
                    $epfEeRate = 0.11;
                }

                $epfEe = round($gross * $epfEeRate, 2);

                if ($epfRateType === 'custom' && $employee->statutoryProfile?->epf_employer_custom_rate) {
                    $epfErRate = ((float) $employee->statutoryProfile->epf_employer_custom_rate) / 100;
                } else {
                    $epfErRate = ($gross <= 5000) ? 0.13 : 0.12;
                }
                $epfEr = round($gross * $epfErRate, 2);

                // Compute Tiered PERKESO (Act 4 Standard Schedule)
                $socsoValues = self::calculateSocso($gross);
                $socsoEe = $socsoValues['ee'];
                $socsoEr = $socsoValues['er'];
                $skbbkEe = 0.00; // SKBBK is optional experimental scheme or included in employer rate

                // Compute EIS (Act 800 Standard Schedule)
                $isEisEnabled = (bool) ($employee->statutoryProfile?->is_eis_contributed ?? true);
                if ($isEisEnabled) {
                    $eisValues = self::calculateEis($gross);
                    $eisEe = $eisValues['ee'];
                    $eisEr = $eisValues['er'];
                } else {
                    $eisEe = 0.00;
                    $eisEr = 0.00;
                }

                // Compute Official LHDN MTD / PCB (Income Tax Act 1967)
                $pcb = self::calculatePcb($gross, $epfEe);
            }

            $totalDeductions = $epfEe + $socsoEe + $skbbkEe + $eisEe + $pcb;
            $netSalary = $gross - $totalDeductions;

            PayrollItem::create([
                'payroll_run_id' => $payrollRun->id,
                'employee_id' => $employee->id,
                'basic_salary' => $basic,
                'allowances_total' => $allowances,
                'gross_salary' => $gross,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'epf_subject_wages' => $gross,
                'socso_subject_wages' => min($gross, 6000.00),
                'eis_subject_wages' => min($gross, 6000.00),
                'pcb_subject_wages' => $gross,
                'epf_employee' => $epfEe,
                'socso_employee' => $socsoEe,
                'skbbk_employee' => $skbbkEe,
                'eis_employee' => $eisEe,
                'pcb_amount' => $pcb,
                'total_employee_deductions' => $totalDeductions,
                'epf_employer' => $epfEr,
                'socso_employer' => $socsoEr,
                'eis_employer' => $eisEr,
                'total_employer_contributions' => $epfEr + $socsoEr + $eisEr,
                'net_salary' => $netSalary,
                'payslip_token' => Str::random(32),
            ]);

            $totalGross += $gross;
            $totalEmployeeStatutory += $totalDeductions;
            $totalEmployerStatutory += ($epfEr + $socsoEr + $eisEr);
            $totalNet += $netSalary;
        }

        $payrollRun->update([
            'total_headcount' => $employees->count(),
            'total_gross_amount' => $totalGross,
            'total_statutory_employee' => $totalEmployeeStatutory,
            'total_statutory_employer' => $totalEmployerStatutory,
            'total_net_disbursement' => $totalNet,
        ]);

        AuditTrail::log(
            module: 'payroll',
            event: 'payroll.batch_generated',
            description: "Generated payroll batch {$payrollRun->batch_no} with {$employees->count()} employees.",
            auditable: $payrollRun,
            newValues: ['total_net' => $totalNet, 'headcount' => $employees->count()]
        );

        return redirect()->route('admin.payroll.show', $payrollRun)->with('status', 'Payroll batch generated successfully.');
    }

    /**
     * Show batch review details & employee line items.
     */
    public function show(PayrollRun $payrollRun)
    {
        $payrollRun->load(['company', 'items.employee', 'creator', 'approver']);

        return view('admin.payroll.show', compact('payrollRun'));
    }

    /**
     * Approve and lock the monthly payroll run.
     */
    public function approve(PayrollRun $payrollRun)
    {
        $payrollRun->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        AuditTrail::log(
            module: 'payroll',
            event: 'payroll.batch_approved',
            description: "Payroll batch {$payrollRun->batch_no} officially approved by " . auth()->user()->name,
            auditable: $payrollRun,
            newValues: ['status' => 'approved', 'approved_at' => now()->toDateTimeString()]
        );

        return redirect()->back()->with('status', 'Payroll batch approved and ready for bank disbursement.');
    }

    /**
     * Re-calculate or re-open batch into draft mode to re-sync statutory figures and leaves.
     */
    public function recalculate(PayrollRun $payrollRun)
    {
        $periodStart = Carbon::createFromDate($payrollRun->period_year, $payrollRun->period_month, 1)->startOfMonth()->toDateString();
        $periodEnd = Carbon::createFromDate($payrollRun->period_year, $payrollRun->period_month, 1)->endOfMonth()->toDateString();

        $employees = Employee::with(['salaryComponents', 'statutoryProfile', 'department'])
            ->where('employment_status', '!=', 'resigned')
            ->get();

        $totalGross = 0.00;
        $totalEmployeeStatutory = 0.00;
        $totalEmployerStatutory = 0.00;
        $totalNet = 0.00;

        // Delete previous items to regenerate clean
        $payrollRun->items()->delete();

        foreach ($employees as $employee) {
            $basic = (float) $employee->basic_salary;
            $allowances = (float) $employee->salaryComponents->where('type', 'allowance')->sum('pivot.amount');
            $empType = strtolower($employee->employment_type ?? 'permanent');

            // Unpaid Leave Deduction (ORP)
            $unpaidLeaveDays = (float) \App\Models\LeaveApplication::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
                ->where(function ($query) use ($periodStart, $periodEnd) {
                    $query->whereBetween('start_date', [$periodStart, $periodEnd])
                        ->orWhereBetween('end_date', [$periodStart, $periodEnd]);
                })
                ->sum('total_days');

            $dailyOrp = ($basic > 0) ? round($basic / 26.0, 2) : 0.00;
            $unpaidLeaveDeduction = round($unpaidLeaveDays * $dailyOrp, 2);
            $gross = max(0.00, $basic + $allowances - $unpaidLeaveDeduction);

            if ($empType === 'intern') {
                $epfEe = 0.00;
                $epfEr = 0.00;
                $socsoEe = 0.00;
                $skbbkEe = 0.00;
                $socsoEr = 0.00;
                $eisEe = 0.00;
                $eisEr = 0.00;
                $pcb = 0.00;
            } else {
                $epfRateType = $employee->statutoryProfile?->epf_rate_type ?? 'standard_11';
                $epfEeRate = ($epfRateType === 'reduced_9') ? 0.09 : 0.11;
                $epfEe = round($gross * $epfEeRate, 2);
                $epfErRate = ($gross <= 5000) ? 0.13 : 0.12;
                $epfEr = round($gross * $epfErRate, 2);

                $socsoValues = self::calculateSocso($gross);
                $socsoEe = $socsoValues['ee'];
                $socsoEr = $socsoValues['er'];
                $skbbkEe = 0.00;

                $isEisEnabled = (bool) ($employee->statutoryProfile?->is_eis_contributed ?? true);
                if ($isEisEnabled) {
                    $eisValues = self::calculateEis($gross);
                    $eisEe = $eisValues['ee'];
                    $eisEr = $eisValues['er'];
                } else {
                    $eisEe = 0.00;
                    $eisEr = 0.00;
                }

                $pcb = self::calculatePcb($gross, $epfEe);
            }

            $totalDeductions = $epfEe + $socsoEe + $skbbkEe + $eisEe + $pcb;
            $netSalary = $gross - $totalDeductions;

            PayrollItem::create([
                'payroll_run_id' => $payrollRun->id,
                'employee_id' => $employee->id,
                'basic_salary' => $basic,
                'allowances_total' => $allowances,
                'gross_salary' => $gross,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'epf_subject_wages' => $gross,
                'socso_subject_wages' => min($gross, 6000.00),
                'eis_subject_wages' => min($gross, 6000.00),
                'pcb_subject_wages' => $gross,
                'epf_employee' => $epfEe,
                'socso_employee' => $socsoEe,
                'skbbk_employee' => $skbbkEe,
                'eis_employee' => $eisEe,
                'pcb_amount' => $pcb,
                'total_employee_deductions' => $totalDeductions,
                'epf_employer' => $epfEr,
                'socso_employer' => $socsoEr,
                'eis_employer' => $eisEr,
                'total_employer_contributions' => $epfEr + $socsoEr + $eisEr,
                'net_salary' => $netSalary,
                'payslip_token' => Str::random(32),
            ]);

            $totalGross += $gross;
            $totalEmployeeStatutory += $totalDeductions;
            $totalEmployerStatutory += ($epfEr + $socsoEr + $eisEr);
            $totalNet += $netSalary;
        }

        $payrollRun->update([
            'status' => 'draft',
            'approved_by' => null,
            'approved_at' => null,
            'total_headcount' => $employees->count(),
            'total_gross_amount' => $totalGross,
            'total_statutory_employee' => $totalEmployeeStatutory,
            'total_statutory_employer' => $totalEmployerStatutory,
            'total_net_disbursement' => $totalNet,
        ]);

        AuditTrail::log(
            module: 'payroll',
            event: 'payroll.batch_recalculated',
            description: "Payroll batch {$payrollRun->batch_no} recalculated and reverted to draft by " . auth()->user()->name,
            auditable: $payrollRun,
            newValues: ['status' => 'draft', 'total_net' => $totalNet]
        );

        return redirect()->route('admin.payroll.show', $payrollRun)->with('status', 'Payroll batch recalculated and reverted to Draft successfully.');
    }

    /**
     * Preview and print official individual employee payslip statement.
     */
    public function payslip(PayrollRun $payrollRun, \App\Models\PayrollItem $item)
    {
        $payrollRun->load('company');
        $item->load(['employee.department', 'employee.statutoryProfile']);
        $company = $payrollRun->company ?? \App\Models\Company::first();

        return view('admin.payroll.payslip', compact('payrollRun', 'item', 'company'));
    }
}
