<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\BankAutopayBatch;
use App\Models\PayrollRun;
use App\Models\StatutorySubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExportAndBankingController extends Controller
{
    /**
     * Display Bank Autopay Batches & Exporter Dashboard.
     */
    public function index()
    {
        $bankBatches = BankAutopayBatch::with(['payrollRun', 'generator'])->latest()->paginate(10);
        $statutorySubmissions = StatutorySubmission::with(['payrollRun', 'exporter'])->latest()->paginate(10);
        $approvedPayrollRuns = PayrollRun::whereIn('status', ['approved', 'paid', 'draft'])->latest()->get();
        $latestPayrollRun = $approvedPayrollRuns->first();

        $totalDisbursed = BankAutopayBatch::sum('total_disbursement_amount');
        $totalStatutoryExported = StatutorySubmission::sum('total_payable_amount');

        return view('admin.banking.index', compact(
            'bankBatches', 
            'statutorySubmissions', 
            'approvedPayrollRuns', 
            'latestPayrollRun',
            'totalDisbursed',
            'totalStatutoryExported'
        ));
    }

    /**
     * Generate and download Maybank2e / CIMB BizChannel / DuitNow batch file for bank disbursement.
     */
    public function generateBankFile(Request $request, PayrollRun $payrollRun)
    {
        $validated = $request->validate([
            'format_type' => ['required', 'in:maybank2e_fixed,cimb_bizchannel_csv,duitnow_txt'],
        ]);

        $payrollRun->load(['items.employee', 'company']);
        $format = $validated['format_type'];
        $batchRef = 'MBB-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        
        $batch = BankAutopayBatch::create([
            'payroll_run_id' => $payrollRun->id,
            'format_type' => $format,
            'batch_reference_no' => $batchRef,
            'total_records' => $payrollRun->items->count(),
            'total_disbursement_amount' => $payrollRun->total_net_disbursement,
            'status' => 'generated',
            'generated_by' => auth()->id(),
        ]);

        AuditTrail::log(
            module: 'banking',
            event: 'bank.file_generated',
            description: "Generated {$format} autopay file for batch {$payrollRun->batch_no} (Total: RM " . number_format($payrollRun->total_net_disbursement, 2) . ").",
            auditable: $batch,
            severity: 'info'
        );

        // Generate Text/CSV Content for download
        $content = "";
        $fileName = "{$batchRef}_{$format}.txt";

        if ($format === 'cimb_bizchannel_csv') {
            $fileName = "{$batchRef}_cimb_bizchannel.csv";
            $content .= "Employee ID,Full Name,Bank Name,Account Number,Amount (MYR),Payment Description\n";
            foreach ($payrollRun->items as $item) {
                $content .= "\"{$item->employee?->employee_no}\",\"{$item->employee?->full_name}\",\"{$item->employee?->bank_name}\",\"{$item->employee?->bank_account_no}\",\"{$item->net_salary}\",\"Salary " . date('F Y', mktime(0,0,0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) . "\"\n";
            }
        } elseif ($format === 'maybank2e_fixed') {
            $content .= "00HDR" . str_pad($payrollRun->company?->name ?? 'PAYFLOW', 30) . date('Ymd') . $batchRef . "\n";
            foreach ($payrollRun->items as $item) {
                $content .= "01DET" . str_pad($item->employee?->employee_no ?? '', 10) . str_pad($item->employee?->full_name ?? '', 40) . str_pad($item->employee?->bank_account_no ?? '', 16) . str_pad(number_format($item->net_salary, 2, '', ''), 12, '0', STR_PAD_LEFT) . "MYR\n";
            }
            $content .= "09TRL" . str_pad($payrollRun->items->count(), 6, '0', STR_PAD_LEFT) . str_pad(number_format($payrollRun->total_net_disbursement, 2, '', ''), 14, '0', STR_PAD_LEFT) . "\n";
        } else {
            $content .= "DUITNOW_BATCH_PAYMENT|" . $batchRef . "|" . date('Y-m-d') . "\n";
            foreach ($payrollRun->items as $item) {
                $content .= "PAYMENT|{$item->employee?->employee_no}|{$item->employee?->full_name}|{$item->employee?->bank_name}|{$item->employee?->bank_account_no}|{$item->net_salary}|MYR\n";
            }
        }

        if ($request->has('download')) {
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        }

        return redirect()->route('admin.banking.index')->with('success', "Bank Autopay batch {$batchRef} generated successfully.");
    }

    /**
     * Generate Statutory Submission files (CP39, EPF i-Akaun, PERKESO ASSIST).
     */
    public function generateStatutoryFile(Request $request, PayrollRun $payrollRun)
    {
        $validated = $request->validate([
            'statutory_body' => ['required', 'in:epf,socso,eis,lhdn_cp39,hrd_corp'],
        ]);

        $payrollRun->load(['items.employee', 'company']);
        $body = $validated['statutory_body'];

        $submission = StatutorySubmission::create([
            'payroll_run_id' => $payrollRun->id,
            'statutory_body' => $body,
            'submission_type' => $body . '_txt',
            'total_payable_amount' => $payrollRun->total_statutory_employee + $payrollRun->total_statutory_employer,
            'status' => 'exported',
            'exported_by' => auth()->id(),
        ]);

        AuditTrail::log(
            module: 'exports',
            event: 'statutory.file_exported',
            description: "Exported {$body} statutory submission file for payroll batch {$payrollRun->batch_no}.",
            auditable: $submission,
            severity: 'info'
        );

        $fileName = "{$payrollRun->batch_no}_{$body}_submission.txt";
        $content = "";

        if ($body === 'epf') {
            $fileName = "{$payrollRun->batch_no}_KWSP_A_Format.csv";
            $content .= "Employer EPF No,Employee No,NRIC/Passport,Full Name,Wages,Employee Share (11%),Employer Share (12%/13%)\n";
            foreach ($payrollRun->items as $item) {
                $content .= "\"{$payrollRun->company?->employer_epf_no}\",\"{$item->employee?->employee_no}\",\"{$item->employee?->nric_passport}\",\"{$item->employee?->full_name}\",\"{$item->gross_salary}\",\"{$item->epf_employee}\",\"{$item->epf_employer}\"\n";
            }
        } elseif ($body === 'socso' || $body === 'eis') {
            $fileName = "{$payrollRun->batch_no}_PERKESO_ASSIST.txt";
            $content .= "PERKESO_ASSIST_2026|" . ($payrollRun->company?->employer_socso_no ?? 'A1234567B') . "|" . $payrollRun->period_year . $payrollRun->period_month . "\n";
            foreach ($payrollRun->items as $item) {
                $content .= "EE|{$item->employee?->employee_no}|{$item->employee?->nric_passport}|{$item->employee?->full_name}|{$item->gross_salary}|{$item->socso_employee}|{$item->skbbk_employee}|{$item->socso_employer}|{$item->eis_employee}|{$item->eis_employer}\n";
            }
        } elseif ($body === 'lhdn_cp39') {
            $fileName = "{$payrollRun->batch_no}_LHDN_CP39.txt";
            $content .= "CP39|" . ($payrollRun->company?->employer_tax_no ?? 'E1234567890') . "|" . $payrollRun->period_month . "/" . $payrollRun->period_year . "\n";
            foreach ($payrollRun->items as $item) {
                $content .= "TX|{$item->employee?->employee_no}|{$item->employee?->nric_passport}|{$item->employee?->full_name}|{$item->gross_salary}|{$item->pcb_amount}\n";
            }
        } else {
            $content .= "HRDCORP|LEVY|" . $payrollRun->batch_no . "\n";
        }

        if ($request->has('download')) {
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
        }

        return redirect()->route('admin.banking.index')->with('success', "Statutory {$body} submission file exported successfully.");
    }
}
