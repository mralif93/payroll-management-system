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
        $query = Employee::with(['department', 'statutoryProfile', 'company', 'salaryComponents.salaryComponent']);

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
        $availableAllowances = \App\Models\SalaryComponent::where('type', 'allowance')->where('is_active', true)->get();

        return view('admin.employees.index', compact('employees', 'departments', 'availableAllowances'));
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
            'email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'string'],
            'employment_type' => ['nullable', 'in:permanent,contract,intern,part_time'],
        ]);

        $employee = Employee::create($validated);

        // Auto-create statutory profile with configured toggles and EPF rate
        $employee->statutoryProfile()->create([
            'epf_rate_type' => $request->input('epf_rate_type', 'standard_11'),
            'epf_employee_custom_rate' => $request->filled('epf_employee_custom_rate') ? (float) $request->input('epf_employee_custom_rate') : null,
            'socso_category' => 'category_1_full',
            'is_eis_contributed' => $request->boolean('is_eis_contributed', true),
            'is_skbbk_contributed' => $request->boolean('is_skbbk_contributed', true),
            'tax_category' => 'single',
            'is_tax_resident' => true,
        ]);

        // Save Allowances if provided
        if ($request->has('allowances') && is_array($request->input('allowances'))) {
            foreach ($request->input('allowances') as $componentId => $amount) {
                if (!empty($amount) && (float) $amount > 0) {
                    $employee->salaryComponents()->create([
                        'salary_component_id' => $componentId,
                        'amount' => (float) $amount,
                        'effective_from' => $validated['joined_date'],
                        'is_recurring' => true,
                    ]);
                }
            }
        }

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
            'email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'string'],
            'nric_passport' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'citizenship' => ['required', 'in:malaysian,permanent_resident,foreign_worker'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'joined_date' => ['required', 'date'],
            'employment_status' => ['required', 'in:active,probation,confirmed,resigned'],
            'employment_type' => ['required', 'in:permanent,contract,intern,part_time'],
            'resigned_date' => ['nullable', 'date'],
            'bank_name' => ['nullable', 'string'],
            'bank_account_no' => ['nullable', 'string'],
            'epf_rate_type' => ['nullable', 'in:standard_11,reduced_9,custom'],
            'epf_employee_custom_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($validated['employment_status'] === 'resigned' && empty($validated['resigned_date'])) {
            $validated['resigned_date'] = now()->toDateString();
        } elseif ($validated['employment_status'] !== 'resigned') {
            $validated['resigned_date'] = null;
        }

        $oldValues = $employee->only(array_keys($validated));
        $employee->update($validated);

        // Update statutory profile toggles and EPF rate
        $employee->statutoryProfile()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'epf_rate_type' => $request->input('epf_rate_type', 'standard_11'),
                'epf_employee_custom_rate' => $request->filled('epf_employee_custom_rate') ? (float) $request->input('epf_employee_custom_rate') : null,
                'is_skbbk_contributed' => $request->boolean('is_skbbk_contributed'),
                'is_eis_contributed' => $request->boolean('is_eis_contributed'),
            ]
        );

        // Synchronize Allowances
        if ($request->has('allowances') && is_array($request->input('allowances'))) {
            foreach ($request->input('allowances') as $componentId => $amount) {
                if (!empty($amount) && (float) $amount > 0) {
                    $employee->salaryComponents()->updateOrCreate(
                        ['employee_id' => $employee->id, 'salary_component_id' => $componentId],
                        [
                            'amount' => (float) $amount,
                            'effective_from' => $employee->joined_date ?? now()->toDateString(),
                            'is_recurring' => true,
                        ]
                    );
                } else {
                    $employee->salaryComponents()->where('salary_component_id', $componentId)->delete();
                }
            }
        }

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
     * Toggle employment status between active and resigned/suspended with optional effective date.
     */
    public function toggleStatus(Request $request, Employee $employee)
    {
        $oldStatus = $employee->employment_status;
        $newStatus = $oldStatus === 'active' ? 'resigned' : 'active';

        $employee->employment_status = $newStatus;
        if ($newStatus === 'resigned') {
            $employee->resigned_date = $request->input('resigned_date', now()->toDateString());
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
            'description' => "Employee {$employee->full_name} ({$employee->employee_no}) {$actionText} (Effective: " . ($employee->resigned_date?->toDateString() ?? 'N/A') . ")",
            'old_values' => ['employment_status' => $oldStatus, 'resigned_date' => $employee->getOriginal('resigned_date')],
            'new_values' => ['employment_status' => $newStatus, 'resigned_date' => $employee->resigned_date?->toDateString()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Employee {$employee->full_name} has been {$actionText}.");
    }
}
