<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeStatutoryProfile;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of active and registered employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'statutoryProfile', 'company']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_no', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $employees = $query->paginate(15);
        $departments = Department::all();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    /**
     * Store a newly created employee and statutory profile.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'employee_no' => ['required', 'string', 'unique:employees,employee_no'],
            'full_name' => ['required', 'string', 'max:255'],
            'nric_passport' => ['required', 'string'],
            'citizenship' => ['required', 'in:malaysian,permanent_resident,foreign_worker'],
            'gender' => ['required', 'in:male,female'],
            'birth_date' => ['required', 'date'],
            'joined_date' => ['required', 'date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'designation' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'bank_account_no' => ['nullable', 'string'],
        ]);

        $employee = Employee::create($validated);

        // Auto-create standard statutory profile
        $employee->statutoryProfile()->create([
            'epf_rate_type' => 'standard_11',
            'socso_category' => 'category_1_full',
            'is_eis_contributed' => true,
            'is_skbbk_contributed' => true,
            'tax_category' => 'single',
            'is_tax_resident' => true,
        ]);

        AuditTrail::create([
            'auditable_type' => Employee::class,
            'auditable_id' => $employee->id,
            'user_id' => auth()->id(),
            'module' => 'employees',
            'event' => 'employee_registered',
            'description' => "Registered new employee {$employee->full_name} ({$employee->employee_no})",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->full_name} successfully registered.");
    }

    /**
     * Display the specified employee profile details.
     */
    public function show(Request $request, Employee $employee)
    {
        $employee->load(['department', 'statutoryProfile', 'dependents', 'salaryComponents.salaryComponent', 'payrollItems', 'company']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'employee' => $employee,
                'statutory' => $employee->statutoryProfile,
                'department_name' => $employee->department?->name ?? 'General',
                'company_name' => $employee->company?->name ?? 'Enterprise Inc',
            ]);
        }

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Update employee statutory profile and salary configuration.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'employment_status' => ['required', 'in:active,probation,confirmed,resigned'],
            'employment_type' => ['required', 'in:permanent,contract,intern,part_time'],
            'bank_name' => ['nullable', 'string'],
            'bank_account_no' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'string'],
        ]);

        $oldValues = $employee->only(array_keys($validated));
        $employee->update($validated);

        AuditTrail::create([
            'auditable_type' => Employee::class,
            'auditable_id' => $employee->id,
            'user_id' => auth()->id(),
            'module' => 'employees',
            'event' => 'employee_updated',
            'description' => "Updated employee profile for {$employee->full_name} ({$employee->employee_no})",
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->full_name} updated successfully.");
    }

    /**
     * Delete an employee record with audit trail.
     */
    public function destroy(Request $request, Employee $employee)
    {
        $oldName = $employee->full_name;
        $oldNo = $employee->employee_no;

        $employee->statutoryProfile()->delete();
        $employee->delete();

        AuditTrail::create([
            'auditable_type' => Employee::class,
            'auditable_id' => $employee->id,
            'user_id' => auth()->id(),
            'module' => 'employees',
            'event' => 'employee_deleted',
            'description' => "Removed employee {$oldName} ({$oldNo}) from roster",
            'old_values' => ['name' => $oldName, 'employee_no' => $oldNo],
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$oldName} removed successfully.");
    }

    /**
     * Toggle employment status between active and resigned/suspended.
     */
    public function toggleStatus(Request $request, Employee $employee)
    {
        $oldStatus = $employee->employment_status;
        $newStatus = $oldStatus === 'active' ? 'resigned' : 'active';

        $employee->employment_status = $newStatus;
        if ($newStatus === 'resigned') {
            $employee->resigned_date = now();
        } else {
            $employee->resigned_date = null;
        }
        $employee->save();

        $actionText = $newStatus === 'resigned' ? 'marked as Resigned / Inactive' : 'Reactivated';

        AuditTrail::create([
            'auditable_type' => Employee::class,
            'auditable_id' => $employee->id,
            'user_id' => auth()->id(),
            'module' => 'employees',
            'event' => $newStatus === 'resigned' ? 'employee_resigned' : 'employee_reactivated',
            'description' => "Employee {$employee->full_name} ({$employee->employee_no}) {$actionText}",
            'old_values' => ['employment_status' => $oldStatus],
            'new_values' => ['employment_status' => $newStatus],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->full_name} has been {$actionText}.");
    }
}
