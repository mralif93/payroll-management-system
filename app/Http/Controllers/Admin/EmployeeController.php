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

        AuditTrail::log(
            module: 'employees',
            event: 'employee.registered',
            description: "New employee {$employee->full_name} ({$employee->employee_no}) registered.",
            auditable: $employee,
            newValues: $validated
        );

        return redirect()->route('admin.employees.index')->with('status', "Employee {$employee->full_name} successfully added.");
    }

    /**
     * Display the specified employee profile details.
     */
    public function show(Employee $employee)
    {
        $employee->load(['department', 'statutoryProfile', 'dependents', 'salaryComponents.salaryComponent', 'payrollItems']);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Update employee statutory profile and salary configuration.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'designation' => ['nullable', 'string'],
            'employment_status' => ['required', 'in:active,probation,confirmed,resigned'],
        ]);

        $oldValues = $employee->only(['basic_salary', 'designation', 'employment_status']);
        $employee->update($validated);

        AuditTrail::log(
            module: 'employees',
            event: 'employee.updated',
            description: "Employee {$employee->full_name} profile updated.",
            auditable: $employee,
            oldValues: $oldValues,
            newValues: $validated
        );

        return redirect()->back()->with('status', 'Employee profile updated successfully.');
    }
}
