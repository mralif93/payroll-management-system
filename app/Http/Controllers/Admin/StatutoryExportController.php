<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\PayrollRun;
use App\Models\StatutorySubmission;
use Illuminate\Http\Request;

class StatutoryExportController extends Controller
{
    /**
     * Display Monthly Statutory Agency Exporters (KWSP, SOCSO, CP39) Dashboard.
     */
    public function index()
    {
        $statutorySubmissions = StatutorySubmission::with(['payrollRun', 'exporter'])->latest()->paginate(10);
        $approvedPayrollRuns = PayrollRun::whereIn('status', ['approved', 'paid', 'draft'])->latest()->get();
        $latestPayrollRun = $approvedPayrollRuns->first();

        $totalStatutoryExported = StatutorySubmission::sum('total_payable_amount');

        return view('admin.tax.exports', compact(
            'statutorySubmissions',
            'approvedPayrollRuns',
            'latestPayrollRun',
            'totalStatutoryExported'
        ));
    }

    /**
     * Generate and download KWSP (EPF Form A) / SOCSO (ASSIST / Form 8A) / LHDN (e-CP39 MTD) file.
     */
    public function generateStatutoryFile(Request $request, PayrollRun $payrollRun)
    {
        $validated = $request->validate([
            'statutory_body' => ['required', 'in:epf,socso,eis,lhdn_cp39'],
        ]);

        $body = $validated['statutory_body'];
        $payrollRun->load(['items.employee', 'company']);

        $filename = '';
        $content = '';
        $totalAmount = 0.00;

        switch ($body) {
            case 'epf':
                $filename = "KWSP_FORMA_{$payrollRun->batch_no}_" . date('Ymd') . ".csv";
                $content = $this->buildEpfFormACsv($payrollRun);
                $totalAmount = $payrollRun->items->sum('epf_employee') + $payrollRun->items->sum('epf_employer');
                break;
            case 'socso':
            case 'eis':
                $filename = "PERKESO_ASSIST_ACT4_SKBBK_{$payrollRun->batch_no}_" . date('Ymd') . ".txt";
                $content = $this->buildSocsoAssistTxt($payrollRun);
                $totalAmount = $payrollRun->items->sum('socso_employee') 
                    + $payrollRun->items->sum('skbbk_employee') 
                    + $payrollRun->items->sum('socso_employer') 
                    + $payrollRun->items->sum('eis_employee') 
                    + $payrollRun->items->sum('eis_employer');
                break;
            case 'lhdn_cp39':
                $filename = "LHDN_CP39_MTD_{$payrollRun->batch_no}_" . date('Ymd') . ".txt";
                $content = $this->buildLhdnCp39Txt($payrollRun);
                $totalAmount = $payrollRun->items->sum('pcb_amount');
                break;
        }

        // Record Statutory Submission Audit Log
        $submission = StatutorySubmission::create([
            'payroll_run_id' => $payrollRun->id,
            'statutory_body' => strtolower($body),
            'submission_type' => 'monthly_declaration',
            'total_payable_amount' => $totalAmount,
            'exported_by' => auth()->id() ?? 1,
        ]);

        AuditTrail::log(
            module: 'tax',
            event: 'tax.statutory_file_generated',
            description: "Exported statutory submission file [{$body}] for batch {$payrollRun->batch_no} (RM {$totalAmount})",
            auditable: $submission
        );

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function buildEpfFormACsv(PayrollRun $payrollRun): string
    {
        $rows = [];
        $rows[] = "Employer No,Employer Name,Contribution Month,Year,Employee No,NRIC/Passport,Employee Name,Wages,Employee Share,Employer Share,Total";
        $epfNo = $payrollRun->company?->epf_no ?? '12345678';
        $compName = $payrollRun->company?->name ?? 'PAYFLOW CORP';

        foreach ($payrollRun->items as $item) {
            $empShare = (float) $item->epf_employee;
            $emprShare = (float) $item->epf_employer;
            $rows[] = sprintf(
                '"%s","%s","%02d","%04d","%s","%s","%s","%.2f","%.2f","%.2f","%.2f"',
                $epfNo,
                $compName,
                $payrollRun->period_month,
                $payrollRun->period_year,
                $item->employee?->employee_no ?? '',
                $item->employee?->nric_passport ?? '',
                str_replace('"', '""', $item->employee_name),
                $item->gross_salary,
                $empShare,
                $emprShare,
                $empShare + $emprShare
            );
        }
        return implode("\r\n", $rows);
    }

    private function buildSocsoAssistTxt(PayrollRun $payrollRun): string
    {
        $lines = [];
        $socsoNo = $payrollRun->company?->socso_no ?? 'A12345678';
        $lines[] = "H|" . $socsoNo . "|" . sprintf("%04d%02d", $payrollRun->period_year, $payrollRun->period_month) . "|ASSIST-ACT4-SKBBK-2026";

        foreach ($payrollRun->items as $item) {
            $lines[] = sprintf(
                "D|%s|%s|%.2f|%.2f|%.2f|%.2f|%.2f",
                $item->employee?->nric_passport ?? '000000000000',
                $item->employee_name,
                $item->gross_salary,
                $item->socso_employee + $item->skbbk_employee,
                $item->socso_employer,
                $item->eis_employee,
                $item->eis_employer
            );
        }
        $lines[] = "T|" . $payrollRun->items->count();
        return implode("\r\n", $lines);
    }

    private function buildLhdnCp39Txt(PayrollRun $payrollRun): string
    {
        $lines = [];
        $taxNo = $payrollRun->company?->tax_no ?? 'E9999999900';
        $lines[] = "CP39|" . $taxNo . "|" . sprintf("%04d%02d", $payrollRun->period_year, $payrollRun->period_month);

        foreach ($payrollRun->items as $item) {
            $lines[] = sprintf(
                "DET|%s|%s|%s|%.2f",
                $item->employee?->income_tax_no ?? 'SG0000000000',
                $item->employee?->nric_passport ?? '',
                $item->employee_name,
                $item->pcb_amount
            );
        }
        $lines[] = "END|" . $payrollRun->items->count() . "|" . number_format($payrollRun->items->sum('pcb_amount'), 2, '.', '');
        return implode("\r\n", $lines);
    }
}
