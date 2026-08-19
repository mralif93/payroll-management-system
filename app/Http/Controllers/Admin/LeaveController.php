<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\EmployeeLeaveBalance;
use App\Models\Department;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display leave dashboard, statistics, and applications roster.
     */
    public function index(Request $request)
    {
        $currentYear = (int) date('Y');
        $today = Carbon::today()->toDateString();

        $query = LeaveApplication::with(['employee.department', 'leaveType', 'approver'])
            ->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->input('leave_type_id'));
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->input('department_id'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_no', 'like', "%{$search}%");
            });
        }

        $leaves = $query->paginate(10)->withQueryString();

        // Key Leave Statistics
        $totalPending = LeaveApplication::where('status', 'pending')->count();
        $totalApprovedMonth = LeaveApplication::where('status', 'approved')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', $currentYear)
            ->count();
        
        $activeOnLeaveToday = LeaveApplication::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();

        $totalUnpaidDaysMonth = LeaveApplication::where('status', 'approved')
            ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $leaveTypes = LeaveType::all();
        $departments = Department::all();
        $employees = Employee::where('employment_status', '!=', 'resigned')->orderBy('full_name')->get();

        // 2. Employee Balances Roster for Tab 2
        $employeeBalancesQuery = Employee::with(['department', 'leaveBalances.leaveType'])
            ->where('employment_status', '!=', 'resigned')
            ->orderBy('full_name');

        if ($request->filled('balance_search')) {
            $bSearch = $request->input('balance_search');
            $employeeBalancesQuery->where(function ($q) use ($bSearch) {
                $q->where('full_name', 'like', "%{$bSearch}%")
                  ->orWhere('employee_no', 'like', "%{$bSearch}%");
            });
        }

        if ($request->filled('balance_dept')) {
            $employeeBalancesQuery->where('department_id', $request->input('balance_dept'));
        }

        $employeeBalances = $employeeBalancesQuery->paginate(10, ['*'], 'balance_page')->withQueryString();

        return view('admin.leaves.index', compact(
            'leaves',
            'employeeBalances',
            'totalPending',
            'totalApprovedMonth',
            'activeOnLeaveToday',
            'totalUnpaidDaysMonth',
            'leaveTypes',
            'departments',
            'employees',
            'currentYear'
        ));
    }

    /**
     * Store new leave application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'total_days' => ['required', 'numeric', 'min:0.5', 'max:90'],
            'status' => ['required', 'in:pending,approved'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $year = Carbon::parse($validated['start_date'])->year;

        $isApproved = ($validated['status'] === 'approved');

        $leave = LeaveApplication::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => (float) $validated['total_days'],
            'status' => $validated['status'],
            'reason' => $validated['reason'] ?? null,
            'approved_by' => $isApproved ? auth()->id() : null,
            'approved_at' => $isApproved ? now() : null,
        ]);

        // Update employee leave balance for the year
        $balance = EmployeeLeaveBalance::firstOrCreate(
            ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $year],
            [
                'total_entitled' => $leaveType->default_days_per_year,
                'taken_days' => 0.0,
                'pending_days' => 0.0,
                'remaining_days' => $leaveType->default_days_per_year,
            ]
        );

        if ($isApproved) {
            $balance->increment('taken_days', $leave->total_days);
            $balance->decrement('remaining_days', $leave->total_days);
        } else {
            $balance->increment('pending_days', $leave->total_days);
        }

        AuditTrail::create([
            'auditable_type' => LeaveApplication::class,
            'auditable_id' => $leave->id,
            'user_id' => auth()->id(),
            'module' => 'leaves',
            'event' => 'leave_recorded',
            'description' => "Recorded {$leave->total_days} days {$leaveType->name} for {$employee->full_name} ({$employee->employee_no})",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.leaves.index')->with('success', "Leave record for {$employee->full_name} successfully recorded.");
    }

    /**
     * Approve or reject a leave application.
     */
    public function updateStatus(Request $request, LeaveApplication $leave)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,cancelled'],
        ]);

        $oldStatus = $leave->status;
        $newStatus = $validated['status'];
        $year = Carbon::parse($leave->start_date)->year;

        $balance = EmployeeLeaveBalance::firstOrCreate(
            ['employee_id' => $leave->employee_id, 'leave_type_id' => $leave->leave_type_id, 'year' => $year],
            ['total_entitled' => $leave->leaveType?->default_days_per_year ?? 14, 'remaining_days' => 14]
        );

        if ($oldStatus === 'pending') {
            $balance->decrement('pending_days', $leave->total_days);
            if ($newStatus === 'approved') {
                $balance->increment('taken_days', $leave->total_days);
                $balance->decrement('remaining_days', $leave->total_days);
            }
        } elseif ($oldStatus === 'approved' && ($newStatus === 'rejected' || $newStatus === 'cancelled')) {
            $balance->decrement('taken_days', $leave->total_days);
            $balance->increment('remaining_days', $leave->total_days);
        }

        $leave->update([
            'status' => $newStatus,
            'approved_by' => ($newStatus === 'approved') ? auth()->id() : null,
            'approved_at' => ($newStatus === 'approved') ? now() : null,
        ]);

        AuditTrail::create([
            'auditable_type' => LeaveApplication::class,
            'auditable_id' => $leave->id,
            'user_id' => auth()->id(),
            'module' => 'leaves',
            'event' => "leave_{$newStatus}",
            'description' => "Updated leave application #{$leave->id} status to {$newStatus}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.leaves.index')->with('success', "Leave application updated to " . strtoupper($newStatus));
    }

    /**
     * Delete a leave application.
     */
    public function destroy(LeaveApplication $leave)
    {
        $year = Carbon::parse($leave->start_date)->year;
        $balance = EmployeeLeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $year)
            ->first();

        if ($balance) {
            if ($leave->status === 'approved') {
                $balance->decrement('taken_days', $leave->total_days);
                $balance->increment('remaining_days', $leave->total_days);
            } elseif ($leave->status === 'pending') {
                $balance->decrement('pending_days', $leave->total_days);
            }
        }

        $leave->delete();

        return redirect()->route('admin.leaves.index')->with('success', "Leave record removed.");
    }

    /**
     * Update individual employee leave balance entitlement.
     */
    public function updateBalance(Request $request, EmployeeLeaveBalance $balance)
    {
        $validated = $request->validate([
            'total_entitled' => ['required', 'numeric', 'min:0', 'max:365'],
            'taken_days' => ['required', 'numeric', 'min:0', 'max:365'],
        ]);

        $oldValues = $balance->only(['total_entitled', 'taken_days', 'remaining_days']);
        $newRemaining = max(0.0, (float) $validated['total_entitled'] - (float) $validated['taken_days']);

        $balance->update([
            'total_entitled' => (float) $validated['total_entitled'],
            'taken_days' => (float) $validated['taken_days'],
            'remaining_days' => $newRemaining,
        ]);

        AuditTrail::create([
            'auditable_type' => EmployeeLeaveBalance::class,
            'auditable_id' => $balance->id,
            'user_id' => auth()->id(),
            'module' => 'leaves',
            'event' => 'leave_balance_adjusted',
            'description' => "Adjusted {$balance->leaveType?->name} quota for {$balance->employee?->full_name} to {$balance->total_entitled} days (Remaining: {$newRemaining}d)",
            'old_values' => $oldValues,
            'new_values' => $balance->only(['total_entitled', 'taken_days', 'remaining_days']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.leaves.index', ['tab' => 'balances'])->with('success', "Leave balance for {$balance->employee?->full_name} ({$balance->leaveType?->name}) updated successfully.");
    }
}
