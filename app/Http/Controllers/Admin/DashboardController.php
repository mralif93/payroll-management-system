<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\StatutoryParameter;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Main Payroll Executive Operations Dashboard.
     */
    public function index()
    {
        $currentBatch = PayrollRun::with(['items.employee'])->latest()->first();
        $totalEmployees = Employee::where('employment_status', 'active')->count();
        $statutoryVersion = StatutoryParameter::latest('effective_from')->first();
        $recentAudits = AuditTrail::with('user')->latest()->take(5)->get();

        $totalNetPay = $currentBatch ? $currentBatch->total_net_disbursement : 0;
        $totalEpfPool = $currentBatch ? ($currentBatch->items->sum('epf_employee') + $currentBatch->items->sum('epf_employer')) : 0;
        $totalSocsoPool = $currentBatch ? ($currentBatch->items->sum('socso_employee') + $currentBatch->items->sum('socso_employer') + $currentBatch->items->sum('eis_employee') + $currentBatch->items->sum('eis_employer') + $currentBatch->items->sum('skbbk_employee')) : 0;
        $totalPcbPool = $currentBatch ? $currentBatch->items->sum('pcb_amount') : 0;

        return view('admin.dashboard', compact(
            'currentBatch',
            'totalEmployees',
            'statutoryVersion',
            'recentAudits',
            'totalNetPay',
            'totalEpfPool',
            'totalSocsoPool',
            'totalPcbPool'
        ));
    }
}
