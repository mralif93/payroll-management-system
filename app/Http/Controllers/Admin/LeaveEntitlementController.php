<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveEntitlementController extends Controller
{
    /**
     * Display Employee Leave Entitlements & Quotas dashboard.
     */
    public function index(Request $request)
    {
        $currentYear = (int) $request->input('year', date('Y'));

        $query = Employee::with(['department', 'leaveBalances' => function ($q) use ($currentYear) {
            $q->where('year', $currentYear)->with('leaveType');
        }])
        ->where('employment_status', '!=', 'resigned')
        ->orderBy('full_name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $employeeBalances = $query->paginate(10)->withQueryString();

        $departments = Department::all();
        $leaveTypes = LeaveType::where('is_paid', true)->get();

        // Calculate aggregate entitlement metrics
        $totalStaff = Employee::where('employment_status', '!=', 'resigned')->count();
        $totalEntitledDays = EmployeeLeaveBalance::where('year', $currentYear)->sum('total_entitled');
        $totalTakenDays = EmployeeLeaveBalance::where('year', $currentYear)->sum('taken_days');
        $totalRemainingDays = EmployeeLeaveBalance::where('year', $currentYear)->sum('remaining_days');

        return view('admin.leaves.entitlements', compact(
            'employeeBalances',
            'departments',
            'leaveTypes',
            'currentYear',
            'totalStaff',
            'totalEntitledDays',
            'totalTakenDays',
            'totalRemainingDays'
        ));
    }

    /**
     * Update/Adjust employee annual leave quotas.
     */
    public function updateBalance(Request $request, EmployeeLeaveBalance $balance)
    {
        $validated = $request->validate([
            'total_entitled' => ['required', 'numeric', 'min:0', 'max:120'],
            'taken_days' => ['required', 'numeric', 'min:0', 'max:120'],
        ]);

        $oldValues = $balance->only(['total_entitled', 'taken_days', 'remaining_days']);
        $remaining = max(0.0, (float) $validated['total_entitled'] - (float) $validated['taken_days']);

        $balance->update([
            'total_entitled' => $validated['total_entitled'],
            'taken_days' => $validated['taken_days'],
            'remaining_days' => $remaining,
        ]);

        AuditTrail::log(
            module: 'leave',
            event: 'leave.balance_adjusted',
            description: "Updated leave quota for employee #{$balance->employee_id} ({$balance->leaveType?->name}): {$validated['total_entitled']} entitled, {$validated['taken_days']} taken.",
            auditable: $balance,
            oldValues: $oldValues,
            newValues: $balance->only(['total_entitled', 'taken_days', 'remaining_days'])
        );

        return redirect()->back()->with('status', 'Employee leave balance updated successfully.');
    }
}
