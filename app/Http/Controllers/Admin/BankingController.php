<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\BankAutopayBatch;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankingController extends Controller
{
    /**
     * Display Bank Autopay Batches & Disbursement Dashboard.
     */
    public function index()
    {
        $bankBatches = BankAutopayBatch::with(['payrollRun', 'generator'])->latest()->paginate(10);
        $approvedPayrollRuns = PayrollRun::whereIn('status', ['approved', 'paid', 'draft'])->latest()->get();
        $latestPayrollRun = $approvedPayrollRuns->first();

        $totalDisbursed = BankAutopayBatch::sum('total_disbursement_amount');

        return view('admin.banking.index', compact(
            'bankBatches',
            'approvedPayrollRuns',
            'latestPayrollRun',
            'totalDisbursed'
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

        $format = $validated['format_type'];
        $payrollRun->load(['items.employee', 'company']);

        $filename = '';
        $content = '';

        switch ($format) {
            case 'maybank2e_fixed':
                $filename = "M2E_PAYROLL_{$payrollRun->batch_no}_" . date('YmdHis') . ".txt";
                $content = $this->buildMaybank2eFixed($payrollRun);
                break;
            case 'cimb_bizchannel_csv':
                $filename = "CIMB_BIZCHANNEL_{$payrollRun->batch_no}_" . date('YmdHis') . ".csv";
                $content = $this->buildCimbBizChannelCsv($payrollRun);
                break;
            case 'duitnow_txt':
                $filename = "DUITNOW_IBG_{$payrollRun->batch_no}_" . date('YmdHis') . ".txt";
                $content = $this->buildDuitnowTxt($payrollRun);
                break;
        }

        // Record Bank Batch Audit Log
        $batch = BankAutopayBatch::create([
            'payroll_run_id' => $payrollRun->id,
            'format_type' => $format,
            'batch_reference_no' => 'BAT-' . strtoupper(Str::random(8)),
            'total_employees_count' => $payrollRun->items->count(),
            'total_disbursement_amount' => $payrollRun->total_net_disbursement,
            'generated_by' => auth()->id() ?? 1,
            'payload_checksum' => hash('sha256', $content),
        ]);

        AuditTrail::log(
            module: 'banking',
            event: 'banking.file_generated',
            description: "Generated bank disbursement file [{$format}] for payroll batch {$payrollRun->batch_no} (RM {$payrollRun->total_net_disbursement})",
            auditable: $batch
        );

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function buildMaybank2eFixed(PayrollRun $payrollRun): string
    {
        $lines = [];
        $header = sprintf("00HDR%-10s%-30s%08d%012d%s", 
            substr($payrollRun->batch_no, 0, 10),
            substr($payrollRun->company?->name ?? 'PAYFLOW CORP', 0, 30),
            $payrollRun->items->count(),
            round($payrollRun->total_net_disbursement * 100),
            date('Ymd')
        );
        $lines[] = str_pad($header, 120, ' ');

        foreach ($payrollRun->items as $item) {
            $bankAcc = preg_replace('/[^0-9]/', '', $item->employee?->bank_account_no ?? '114012345678');
            $line = sprintf("02%-16s%-40s%010d%-20s%-12s",
                substr($bankAcc, 0, 16),
                substr($item->employee_name, 0, 40),
                round($item->net_salary * 100),
                substr($item->employee?->nric_passport ?? '', 0, 20),
                'SALARY ' . date('M')
            );
            $lines[] = str_pad($line, 120, ' ');
        }

        $trailer = sprintf("99TRL%08d%014d", $payrollRun->items->count(), round($payrollRun->total_net_disbursement * 100));
        $lines[] = str_pad($trailer, 120, ' ');

        return implode("\r\n", $lines);
    }

    private function buildCimbBizChannelCsv(PayrollRun $payrollRun): string
    {
        $rows = [];
        $rows[] = "Employee ID,Full Name,Bank Name,Account Number,Payment Amount,Payment Reference,Beneficiary ID";
        foreach ($payrollRun->items as $item) {
            $rows[] = sprintf(
                '"%s","%s","%s","%s","%.2f","%s","%s"',
                $item->employee?->employee_no ?? '',
                str_replace('"', '""', $item->employee_name),
                $item->employee?->bank_name ?? 'CIMB Bank',
                $item->employee?->bank_account_no ?? '8001234567',
                $item->net_salary,
                "SALARY " . $payrollRun->period_month . '/' . $payrollRun->period_year,
                $item->employee?->nric_passport ?? ''
            );
        }
        return implode("\r\n", $rows);
    }

    private function buildDuitnowTxt(PayrollRun $payrollRun): string
    {
        $lines = [];
        $lines[] = "H|" . date('Ymd') . "|" . $payrollRun->batch_no . "|" . $payrollRun->items->count() . "|" . number_format($payrollRun->total_net_disbursement, 2, '.', '');
        foreach ($payrollRun->items as $item) {
            $lines[] = sprintf(
                "D|%s|%s|%.2f|%s|%s|%s",
                $item->employee?->bank_account_no ?? '1234567890',
                $item->employee_name,
                $item->net_salary,
                $item->employee?->bank_name ?? 'Maybank',
                $item->employee?->nric_passport ?? '',
                "Payroll " . date('M Y')
            );
        }
        $lines[] = "T|" . $payrollRun->items->count();
        return implode("\r\n", $lines);
    }
}
