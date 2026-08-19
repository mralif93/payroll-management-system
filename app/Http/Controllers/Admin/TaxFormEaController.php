<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Employee;
use App\Models\TaxFormEaRecord;
use Illuminate\Http\Request;

class TaxFormEaController extends Controller
{
    /**
     * Display list of compiled Year-End Borang EA (C.P.8A) tax records.
     */
    public function index(Request $request)
    {
        $taxYear = $request->input('tax_year', date('Y'));
        $search = $request->input('search');

        $query = TaxFormEaRecord::with('employee')
            ->where('tax_year', $taxYear);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('serial_no', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($empQ) use ($search) {
                      $empQ->where('full_name', 'like', "%{$search}%")
                           ->orWhere('employee_no', 'like', "%{$search}%")
                           ->orWhere('nric_passport', 'like', "%{$search}%");
                  });
            });
        }

        $eaRecords = $query->paginate(15)->withQueryString();

        $totalAccumulatedPcb = TaxFormEaRecord::where('tax_year', $taxYear)->sum('total_pcb_mtd');
        $totalKwspEe = TaxFormEaRecord::where('tax_year', $taxYear)->sum('total_epf_employee');
        $totalGrossEarnings = TaxFormEaRecord::where('tax_year', $taxYear)->sum('gross_salary_wages');

        return view('admin.tax.ea', compact('eaRecords', 'taxYear', 'totalAccumulatedPcb', 'totalKwspEe', 'totalGrossEarnings'));
    }

    /**
     * Compile annual Form EA records for all employees.
     */
    public function compileAnnual(Request $request)
    {
        $validated = $request->validate([
            'tax_year' => ['required', 'string', 'size:4'],
        ]);

        $employees = Employee::with(['payrollItems' => function ($q) use ($validated) {
            $q->whereHas('payrollRun', function ($runQuery) use ($validated) {
                $runQuery->where('period_year', $validated['tax_year'])
                         ->whereIn('status', ['approved', 'paid', 'locked', 'draft']);
            });
        }])->get();

        $compiledCount = 0;

        foreach ($employees as $employee) {
            $items = $employee->payrollItems;
            
            $grossWages = $items->sum('basic_salary') + $items->sum('allowances_total') + $items->sum('overtime_total');
            $bonus = $items->sum('bonus_amount');
            $pcb = $items->sum('pcb_amount');
            $zakat = $items->sum('zakat_amount');
            $epf = $items->sum('epf_employee');
            $socso = $items->sum('socso_employee') + $items->sum('skbbk_employee');
            $eis = $items->sum('eis_employee');

            TaxFormEaRecord::updateOrCreate(
                ['employee_id' => $employee->id, 'tax_year' => $validated['tax_year']],
                [
                    'serial_no' => 'EA-' . $validated['tax_year'] . '-' . $employee->employee_no,
                    'employer_e_no' => $employee->company?->tax_no ?? 'E 9999999900',
                    'gross_salary_wages' => $grossWages,
                    'fees_commission_bonus' => $bonus,
                    'total_pcb_mtd' => $pcb,
                    'total_zakat_paid' => $zakat,
                    'total_epf_employee' => $epf,
                    'total_socso_employee' => $socso,
                    'total_eis_employee' => $eis,
                ]
            );

            $compiledCount++;
        }

        AuditTrail::log(
            module: 'tax',
            event: 'tax.ea_compiled',
            description: "Compiled {$compiledCount} Form EA records for Tax Year {$validated['tax_year']}.",
            severity: 'info'
        );

        return redirect()->back()->with('status', "Compiled {$compiledCount} Form EA records for Tax Year {$validated['tax_year']}.");
    }

    /**
     * Preview and print official LHDN Borang EA (C.P.8A) statement.
     */
    public function print(TaxFormEaRecord $record)
    {
        $record->load(['employee.department', 'employee.statutoryProfile', 'employee.company']);
        $company = $record->employee?->company ?? \App\Models\Company::first();

        return view('admin.tax.ea-statement', compact('record', 'company'));
    }
}
