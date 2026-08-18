<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Services\Payroll\StatutoryParameterResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayrollRunController extends Controller
{
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

        foreach ($employees as $employee) {
            $basic = (float) $employee->basic_salary;
            $allowances = (float) $employee->salaryComponents->where('salaryComponent.type', 'allowance')->sum('amount');
            $gross = $basic + $allowances;

            $empType = $employee->employment_type ?? 'permanent';

            // 1. Interns (Practical Students receiving allowance) are legally exempt from mandatory EPF, SOCSO & EIS
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
                // 2. Permanent, Contract & Part-Time Staff: Full Statutory Calculation
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

                // Compute Tiered PERKESO (Base Act 4 + Optional June 2026 SKBBK)
                $isSkbbkEnabled = (bool) ($employee->statutoryProfile?->is_skbbk_contributed ?? true);
                $socsoEe = ($gross <= 2000) ? 9.90 : min(29.90, round($gross * 0.005, 2));
                $skbbkEe = $isSkbbkEnabled ? (($gross <= 2000) ? 14.50 : min(43.50, round($gross * 0.00725, 2))) : 0.00;
                $socsoEr = ($gross <= 2000) ? 34.15 : min(104.15, round($gross * 0.0175, 2));

                // Compute EIS (0.2% EE, 0.2% ER capped @ RM6k)
                $isEisEnabled = (bool) ($employee->statutoryProfile?->is_eis_contributed ?? true);
                $eisWage = min($gross, 6000.00);
                $eisEe = $isEisEnabled ? round($eisWage * 0.002, 2) : 0.00;
                $eisEr = $isEisEnabled ? round($eisWage * 0.002, 2) : 0.00;

                // Compute PCB Estimation
                $pcb = ($gross > 3500) ? round(($gross - 3500) * 0.08, 2) : 0.00;
            }

            $totalDeductions = $epfEe + $socsoEe + $skbbkEe + $eisEe + $pcb;
            $netSalary = $gross - $totalDeductions;

            PayrollItem::create([
                'payroll_run_id' => $payrollRun->id,
                'employee_id' => $employee->id,
                'basic_salary' => $basic,
                'allowances_total' => $allowances,
                'gross_salary' => $gross,
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
