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

        return view('admin.dashboard', compact(
            'currentBatch',
            'totalEmployees',
            'statutoryVersion',
            'recentAudits'
        ));
    }
}
