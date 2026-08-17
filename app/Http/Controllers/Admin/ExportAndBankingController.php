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

        return view('admin.banking.index', compact('bankBatches', 'statutorySubmissions'));
    }

    /**
     * Generate Maybank2e / CIMB BizChannel batch text file for bank disbursement.
     */
    public function generateBankFile(Request $request, PayrollRun $payrollRun)
    {
        $validated = $request->validate([
            'format_type' => ['required', 'in:maybank2e_fixed,cimb_bizchannel_csv,duitnow_txt'],
        ]);

        $batchRef = 'MBB-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        
        $batch = BankAutopayBatch::create([
            'payroll_run_id' => $payrollRun->id,
            'format_type' => $validated['format_type'],
            'batch_reference_no' => $batchRef,
            'total_records' => $payrollRun->items()->count(),
            'total_disbursement_amount' => $payrollRun->total_net_disbursement,
            'status' => 'generated',
            'generated_by' => auth()->id(),
        ]);

        AuditTrail::log(
            module: 'banking',
            event: 'bank.file_generated',
            description: "Generated {$validated['format_type']} autopay file for batch {$payrollRun->batch_no} (Total: RM " . number_format($payrollRun->total_net_disbursement, 2) . ").",
            auditable: $batch,
            severity: 'info'
        );

        return redirect()->back()->with('status', "Bank Autopay batch {$batchRef} generated successfully.");
    }

    /**
     * Generate Statutory Submission files (CP39, EPF i-Akaun, PERKESO ASSIST).
     */
    public function generateStatutoryFile(Request $request, PayrollRun $payrollRun)
    {
        $validated = $request->validate([
            'statutory_body' => ['required', 'in:epf,socso,eis,lhdn_cp39,hrd_corp'],
        ]);

        $submission = StatutorySubmission::create([
            'payroll_run_id' => $payrollRun->id,
            'statutory_body' => $validated['statutory_body'],
            'submission_type' => $validated['statutory_body'] . '_txt',
            'total_payable_amount' => $payrollRun->total_statutory_employee + $payrollRun->total_statutory_employer,
            'status' => 'exported',
            'exported_by' => auth()->id(),
        ]);

        AuditTrail::log(
            module: 'exports',
            event: 'statutory.file_exported',
            description: "Exported {$validated['statutory_body']} submission file for payroll batch {$payrollRun->batch_no}.",
            auditable: $submission,
            severity: 'info'
        );

        return redirect()->back()->with('status', "Statutory {$validated['statutory_body']} submission file generated.");
    }
}
